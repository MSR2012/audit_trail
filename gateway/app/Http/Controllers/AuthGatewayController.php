<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Services\Gateways\GatewayServiceInterface;
use Illuminate\Http\JsonResponse;

class AuthGatewayController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private GatewayServiceInterface $gatewayService
    )
    {
    }

    public function login(): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('POST', '/authentication/login')
        );
    }

    public function logout(): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('POST', '/authentication/logout')
        );
    }

    public function refreshToken(): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('POST', '/authentication/refresh_token')
        );
    }
}
