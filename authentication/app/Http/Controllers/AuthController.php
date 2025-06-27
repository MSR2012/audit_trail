<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidTokenException;
use App\Exceptions\InvalidLoginCredentialsException;
use App\Exceptions\TooManyFailedAttemptsException;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Throttle\ThrottleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
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
                'errors' => $e->errors(),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
            $this->authService->logout($token);

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
