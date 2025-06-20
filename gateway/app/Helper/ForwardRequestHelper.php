<?php

namespace App\Helper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ForwardRequestHelper
{
    public static function handle(
        string $method,
        string $url
    ): JsonResponse
    {
        try {
            $client = new Client();
            $response = $client->request(
                $method,
                $url,
                [
                    'headers' => array_merge(['Origin' => env('AT_GATEWAY_BASE_URL')], request()->headers->all()),
                    'body' => request()->getContent(),
                ]
            );

            return response()->json(json_decode($response->getBody()->getContents()), $response->getStatusCode())->withHeaders($response->getHeaders());
        } catch (GuzzleException $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Service unavailable'], 503);
        }
    }
}
