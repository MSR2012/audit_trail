<?php

namespace App\Helper;

use App\Dtos\GatewayResponseDto;
use Illuminate\Http\JsonResponse;

class ResponseFormatter
{
    public static function format(GatewayResponseDto $gatewayResponseDto): JsonResponse
    {
        if (empty($gatewayResponseDto->headers)) {
            return response()->json($gatewayResponseDto->body, $gatewayResponseDto->code);
        }
        return response()->json($gatewayResponseDto->body, $gatewayResponseDto->code)->withHeaders($gatewayResponseDto->headers);
    }
}
