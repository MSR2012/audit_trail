<?php

namespace App\Http\Controllers;

use App\Actions\Sessions\CreateSession;
use App\Actions\Sessions\DeleteSession;
use App\Actions\Sessions\UpdateSession;
use App\Dtos\CreateSessionDto;
use App\Dtos\UpdateSessionDto;
use App\Jobs\BlacklistToken;
use App\Models\User;
use App\Repositories\SessionRepository;
use App\Repositories\UserRepository;
use App\Services\Securities\EncoderInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    /**
     */
    public function __construct(
        private readonly UserRepository    $userRepository,
        private readonly SessionRepository $sessionRepository,
    )
    {
    }

    public function login(
        Request          $request,
        EncoderInterface $encoderService,
        CreateSession    $createSession,
        DeleteSession    $deleteSession
    ): JsonResponse
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            if ($this->tooManyFailedAttempts()) {
                return response()->json([
                    'error_message' => 'Too many attempts.',
                ], ResponseAlias::HTTP_TOO_MANY_REQUESTS);
            }

            $user = $this->userRepository->getByEmail($request->input('email'));
            if ($this->isInvalidCredentials($user, $request->password)) {
                RateLimiter::hit($this->throttleKey(), 60);

                return response()->json([
                    'error_message' => 'Invalid credentials.',
                ], ResponseAlias::HTTP_UNAUTHORIZED);
            }

            $existingSessions = $this->sessionRepository->allByUserId($user->id);
            foreach ($existingSessions as $existingSession) {
                $jti = $existingSession->uuid;
                $deleteSession->execute($existingSession);
                dispatch(new BlacklistToken($jti));
            }

            $uuid = Str::uuid()->toString();
            $tokenPayload = $encoderService->encode([
                'user_id' => $user->id,
                'role' => $user->role,
                'jti' => $uuid,
            ]);
            $refreshTokenPayload = $encoderService->encode([
                'user_id' => $user->id,
                'role' => $user->role,
                'jti' => $uuid,
            ], 'refresh');
            $session = $createSession->execute(
                CreateSessionDto::createFromArray([
                    'user_id' => $user->id,
                    'uuid' => $uuid,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'token' => $tokenPayload['token'],
                    'token_expires_at' => $tokenPayload['exp'],
                    'refresh_token' => $refreshTokenPayload['token'],
                    'refresh_token_expires_at' => $refreshTokenPayload['exp'],
                ])
            );

            RateLimiter::clear($this->throttleKey());

            return response()->json([
                'token' => $session->token,
                'token_expires_at' => $session->token_expires_at,
                'refresh_token' => $session->refresh_token,
                'refresh_token_expires_at' => $session->refresh_token_expires_at,
                'user' => $user,
            ], ResponseAlias::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function logout(
        Request       $request,
        DeleteSession $deleteSession
    ): JsonResponse
    {
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $token);
        $session = $this->sessionRepository->getByToken($token);
        if (!$session) {
            return response()->json([
                'success_message' => 'Invalid token.',
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $jti = $session->uuid;
        $deleteSession->execute($session);
        dispatch(new BlacklistToken($jti));

        return response()->json([
            'success_message' => 'Logged out successfully.',
        ], ResponseAlias::HTTP_OK);
    }

    public function refreshToken(
        Request          $request,
        EncoderInterface $encoderService,
        UpdateSession    $updateSession
    ): JsonResponse
    {
        $refreshToken = $request->header('Refresh-Token');
        if (!$refreshToken) {
            return response()->json([
                'error_message' => 'Refresh token is missing.',
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $session = $this->sessionRepository->getByRefreshToken($refreshToken);
        if (!$session || $session->refresh_token_expires_at < Carbon::now()) {
            return response()->json([
                'error_message' => 'Invalid refresh token.',
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->getByUserId($session->user_id);
        $tokenPayload = $encoderService->encode([
            'user_id' => $user->id,
            'role' => $user->role,
            'jti' => $session->uuid,
        ]);
        $session = $updateSession->execute($session, UpdateSessionDto::createFromArray([
            'token' => $tokenPayload['token'],
            'token_expires_at' => $tokenPayload['exp'],
        ]));

        return response()->json([
            'token' => $session->token,
            'token_expires_at' => $session->token_expires_at,
            'refresh_token' => $session->refresh_token,
            'refresh_token_expires_at' => $session->refresh_token_expires_at,
            'user' => $user,
        ], ResponseAlias::HTTP_OK);
    }

    private function isInvalidCredentials(
        ?User  $user,
        string $password
    ): bool
    {
        return !$user || !Hash::check($password, $user->password);
    }

    public function throttleKey(): string
    {
        return request()->ip();
    }

    public function tooManyFailedAttempts(): bool
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return false;
        }

        return true;
    }
}
