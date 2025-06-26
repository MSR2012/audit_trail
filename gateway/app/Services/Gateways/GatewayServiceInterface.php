<?php

namespace App\Services\Gateways;

use App\Dtos\GatewayResponseDto;

interface GatewayServiceInterface
{
    public function forwardRequest(string $method, string $url): GatewayResponseDto;
}
