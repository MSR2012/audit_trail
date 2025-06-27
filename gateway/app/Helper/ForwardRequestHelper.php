<?php

namespace App\Helper;

use App\Dtos\GatewayResponseDto;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ForwardRequestHelper
{
    public static function handle(
        string $method,
        string $url,
        array  $payload = [],
    ): GatewayResponseDto
    {
        try {
            $client = new Client();
            $response = $client->request(
                $method,
                $url,
                $payload
            );

            return GatewayResponseDto::createFromArray([
                'code' => $response->getStatusCode(),
                'body' => json_decode($response->getBody()->getContents(), true),
                'headers' => $response->getHeaders(),
            ]);
        } catch (RequestException $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return GatewayResponseDto::createFromArray([
                'code' => $e->getResponse()->getStatusCode(),
                'body' => json_decode($e->getResponse()->getBody()->getContents(), true),
                'headers' => $e->getResponse()->getHeaders(),
            ]);
        } catch (GuzzleException $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return GatewayResponseDto::createFromArray([
                'code' => ResponseAlias::HTTP_SERVICE_UNAVAILABLE,
                'body' => ['error' => 'Service unavailable'],
                'headers' => [],
            ]);
        }
    }
}
