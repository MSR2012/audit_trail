<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Services\Gateways\GatewayServiceInterface;

class AppGatewayController extends Controller
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

    public function ips()
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('GET', 'app/ips')
        );
    }
}
