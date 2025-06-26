<?php

namespace App\Services\Gateways;

use App\Dtos\GatewayResponseDto;
use App\Helper\ForwardRequestHelper;
use Illuminate\Http\Request;

class AuthGatewayService implements GatewayServiceInterface
{
    private string $baseUrl;
    private string $origin;

    public function __construct(
        private readonly Request $request
    )
    {
        $this->baseUrl = rtrim(env('AT_AUTHENTICATION_BASE_URL'), '/');
        $this->origin = rtrim(env('AT_GATEWAY_BASE_URL'), '/');
    }

    public function forwardRequest(
        string $method,
        string $url,
    ): GatewayResponseDto
    {
        return ForwardRequestHelper::handle(
            $method,
            $this->baseUrl . './' . ltrim($url, '/'),
            [
                'headers' => array_merge(['Origin' => $this->origin], $this->request->headers->all()),
                'body' => $this->request->getContent(),
            ]
        );
    }
}
