<?php

namespace App\Http\Controllers;

use App\Actions\Sessions\CreateSession;
use App\Actions\Sessions\DeleteSession;
use App\Actions\Sessions\UpdateSession;
use App\Dtos\CreateSessionDto;
use App\Dtos\UpdateSessionDto;
use App\Models\User;
use App\Repositories\SessionRepository;
use App\Repositories\UserRepository;
use App\Services\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
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

    /**
     * @throws ValidationException
     */
    public function login(
        Request       $request,
        JwtService    $jwtService,
        CreateSession $createSession,
        DeleteSession $deleteSession
    ): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = $this->userRepository->getByEmail($request->input('email'));
        if ($this->isInvalidCredentials($user, $request->password)) {
            return response()->json([
                'error_message' => 'Invalid credentials.',
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $existingSessions = $this->sessionRepository->allByUserId($user->id);
        foreach ($existingSessions as $existingSession) {
            $deleteSession->execute($existingSession);
        }

        $uuid = Str::uuid()->toString();
        $tokenPayload = $jwtService->encode([
            'user_id' => $user->id,
            'role' => $user->role,
            'jti' => $uuid,
        ]);
        $refreshTokenPayload = $jwtService->encode([
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

        return response()->json([
            'token' => $session->token,
            'token_expires_at' => $session->token_expires_at,
            'refresh_token' => $session->refresh_token,
            'refresh_token_expires_at' => $session->refresh_token_expires_at,
            'user' => $user,
        ], ResponseAlias::HTTP_OK);
    }

    public function logout(
        Request       $request,
        DeleteSession $deleteSession
    ): JsonResponse
    {
        $token = $request->header('Authorization');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            $session = $this->sessionRepository->getByToken($token);
            if ($session) {
                $deleteSession->execute($session);
            }
        }

        return response()->json([
            'success_message' => 'Logged out successfully.',
        ], ResponseAlias::HTTP_OK);
    }

    public function refreshToken(
        Request       $request,
        JwtService    $jwtService,
        UpdateSession $updateSession
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
        $tokenPayload = $jwtService->encode([
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
}
