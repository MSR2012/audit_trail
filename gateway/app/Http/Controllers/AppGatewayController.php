<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Services\Gateways\GatewayServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AppGatewayController extends Controller
{
    private const IP_URL_BASE = 'app/ips';

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

    public function ipIndex(): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('GET', self::IP_URL_BASE)
        );
    }

    public function ipStore(): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('POST', self::IP_URL_BASE)
        );
    }

    public function ipShow($id): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('GET', self::IP_URL_BASE . '/' . $id)
        );
    }

    public function ipUpdate($id): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('PUT', self::IP_URL_BASE . '/' . $id)
        );
    }

    public function ipDelete($id): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('DELETE', self::IP_URL_BASE . '/' . $id)
        );
    }

    public function auditLogIndex(): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('GET', 'app/audit_log')
        );
    }

    public function auditLogViewByLoggedInUser(int $changes_within): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('GET', 'app/audit_log/view-by-user/' . $changes_within)
        );
    }

    public function auditLogViewByUser(int $user_id, int $changes_within): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('GET', 'app/audit_log/view-by-user/' . $user_id . '/' . $changes_within)
        );
    }

    public function auditLogViewByIp(string $ip_address, int $changes_within): JsonResponse
    {
        return ResponseFormatter::format(
            $this->gatewayService->forwardRequest('GET', 'app/audit_log/view-by-ip/' . $ip_address . '/' . $changes_within)
        );
    }
}
