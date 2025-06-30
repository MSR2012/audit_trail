<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidTokenException;
use App\Exceptions\InvalidLoginCredentialsException;
use App\Exceptions\TooManyFailedAttemptsException;
use App\Jobs\AuditLogTrigger;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Throttle\ThrottleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    private const ACTION_LOGIN = 1;
    private const ACTION_LOGOUT = 2;

    /**
     */
    public function __construct(
        private readonly AuthServiceInterface     $authService,
        private readonly ThrottleServiceInterface $throttleService
    )
    {
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            if ($this->throttleService->tooManyFailedAttempts()) {
                throw new TooManyFailedAttemptsException('Too many attempts.');
            }

            $responseBody = $this->authService->login(
                $request->input('email'),
                $request->input('password'),
                $request->ip(),
                $request->header('User-Agent')
            );
            $this->throttleService->clear();

            $user = $responseBody['user'];
            dispatch(new AuditLogTrigger(
                array_merge($request->headers->all(), [
                    'AT-USER-ID' => (int)$user->id,
                    'AT-USER-NAME' => $user->name,
                    'AT-ROLE' => (int)$user->role,
                    'AT-JTI' => $responseBody['jti'],
                ]),
                self::ACTION_LOGIN,
                'Logged in successfully.',
            ));
            unset($responseBody['jti']);

            return response()->json($responseBody, ResponseAlias::HTTP_OK);
        } catch (TooManyFailedAttemptsException $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        } catch (InvalidLoginCredentialsException $e) {
            $this->throttleService->hit();

            return response()->json([
                'error_message' => $e->getMessage(),
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        } catch (ValidationException $e) {
            return response()->json([
                'error_message' => $this->getMessageFromErrors($e->errors()),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
            $this->authService->logout($token);

            dispatch(new AuditLogTrigger(
                $request->headers->all(),
                self::ACTION_LOGOUT,
                'Logged out successfully.',
            ));

            return response()->json([
                'success_message' => 'Logged out successfully.',
            ], ResponseAlias::HTTP_OK);
        } catch (InvalidTokenException $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $refreshToken = $request->header('Refresh-Token');
            if (!$refreshToken) {
                throw new InvalidTokenException('Refresh token is missing.');
            }

            return response()->json($this->authService->refreshToken($refreshToken), ResponseAlias::HTTP_OK);
        } catch (InvalidTokenException $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
