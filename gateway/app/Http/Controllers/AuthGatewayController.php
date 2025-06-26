<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Services\Gateways\GatewayServiceInterface;

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

    public function login()
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('POST', '/authentication/login')
        );
    }

    public function logout()
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('POST', '/authentication/logout')
        );
    }

    public function refreshToken()
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('POST', '/authentication/refresh_token')
        );
    }
}
