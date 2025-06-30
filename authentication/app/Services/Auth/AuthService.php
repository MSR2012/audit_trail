<?php

namespace App\Services\Auth;

use App\Actions\Sessions\CreateSession;
use App\Actions\Sessions\DeleteSession;
use App\Actions\Sessions\UpdateSession;
use App\Dtos\CreateSessionDto;
use App\Dtos\UpdateSessionDto;
use App\Exceptions\InvalidTokenException;
use App\Exceptions\InvalidLoginCredentialsException;
use App\Jobs\BlacklistToken;
use App\Models\User;
use App\Repositories\SessionRepository;
use App\Repositories\UserRepository;
use App\Services\Securities\EncoderInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserRepository    $userRepository,
        private readonly SessionRepository $sessionRepository,
        private readonly EncoderInterface  $encoderService,
        private readonly CreateSession     $createSession,
        private readonly UpdateSession     $updateSession,
        private readonly DeleteSession     $deleteSession,
    )
    {
    }

    /**
     * @throws InvalidLoginCredentialsException
     */
    public function login(string $email, string $password, string $ip = '', string $userAgent = ''): array
    {
        $user = $this->userRepository->getByEmail($email);
        if ($this->isInvalidCredentials($user, $password)) {
            throw new InvalidLoginCredentialsException('Invalid credentials.');
        }

        $existingSessions = $this->sessionRepository->allByUserId($user->id);
        foreach ($existingSessions as $existingSession) {
            $jti = $existingSession->uuid;
            $token_expires_at = $existingSession->token_expires_at;
            $this->deleteSession->execute($existingSession);

            dispatch(new BlacklistToken($jti, $token_expires_at));
        }

        $uuid = Str::uuid()->toString();
        $tokenPayload = $this->encoderService->encode([
            'user_id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'jti' => $uuid,
        ]);
        $refreshTokenPayload = $this->encoderService->encode([
            'user_id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'jti' => $uuid,
        ], 'refresh');
        $session = $this->createSession->execute(
            CreateSessionDto::createFromArray([
                'user_id' => $user->id,
                'uuid' => $uuid,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'token' => $tokenPayload['token'],
                'token_expires_at' => $tokenPayload['exp'],
                'refresh_token' => $refreshTokenPayload['token'],
                'refresh_token_expires_at' => $refreshTokenPayload['exp'],
            ])
        );

        return [
            'token' => $session->token,
            'token_expires_at' => $session->token_expires_at,
            'refresh_token' => $session->refresh_token,
            'refresh_token_expires_at' => $session->refresh_token_expires_at,
            'user' => $user,
            'jti' => $uuid,
        ];
    }

    /**
     * @throws InvalidTokenException
     */
    public function logout(string $token): void
    {
        $session = $this->sessionRepository->getByToken($token);
        if (!$session) {
            throw new InvalidTokenException('Invalid token.');
        }

        $jti = $session->uuid;
        $token_expires_at = $session->token_expires_at;
        $this->deleteSession->execute($session);

        dispatch(new BlacklistToken($jti, $token_expires_at));
    }

    /**
     * @throws InvalidTokenException
     */
    public function refreshToken(string $refreshToken): array
    {
        $session = $this->sessionRepository->getByRefreshToken($refreshToken);
        if (!$session || $session->refresh_token_expires_at < Carbon::now()) {
            throw new InvalidTokenException('Invalid refresh token.');
        }

        $user = $this->userRepository->getByUserId($session->user_id);
        $tokenPayload = $this->encoderService->encode([
            'user_id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'jti' => $session->uuid,
        ]);
        $session = $this->updateSession->execute($session, UpdateSessionDto::createFromArray([
            'token' => $tokenPayload['token'],
            'token_expires_at' => $tokenPayload['exp'],
        ]));

        return [
            'token' => $session->token,
            'token_expires_at' => $session->token_expires_at,
            'refresh_token' => $session->refresh_token,
            'refresh_token_expires_at' => $session->refresh_token_expires_at,
            'user' => $user,
        ];
    }

    private function isInvalidCredentials(?User $user, string $password): bool
    {
        return !$user || !Hash::check($password, $user->password);
    }
}
