<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Services\Gateways\GatewayServiceInterface;
use Illuminate\Http\JsonResponse;

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

    public function ips(): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('GET', 'app/ips')
        );
    }

    public function auditLogViewByUser(int $changes_made_within = 1): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('GET', 'app/audit_log/view-by-user/' . $changes_made_within)
        );
    }
}
