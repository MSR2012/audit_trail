<?php

namespace App\Services\Gateways;

use App\Dtos\GatewayResponseDto;
use App\Helper\ForwardRequestHelper;
use Illuminate\Http\Request;

class AppGatewayService implements GatewayServiceInterface
{
    private string $baseUrl;
    private string $origin;

    public function __construct(private readonly Request $request)
    {
        $this->baseUrl = rtrim(env('AT_APP_BASE_URL'), '/');
        $this->origin = rtrim(env('AT_GATEWAY_BASE_URL'), '/');
    }

    public function forwardRequest(string $method, string $url): GatewayResponseDto
    {
        $queryString = $this->request->getQueryString();
        $query = [];
        foreach (explode('&', $queryString) as $param) {
            $params = explode('=', $param);
            if (count($params) == 2) {
                $query[$params[0]] = $params[1];
            }
        }

        return ForwardRequestHelper::handle(
            $method,
            $this->baseUrl . './' . ltrim($url, '/'),
            [
                'headers' => array_merge(['Origin' => $this->origin], $this->request->headers->all()),
                'body' => $this->request->getContent(),
                'query' => $query
            ]
        );
    }
}
