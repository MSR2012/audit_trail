<?php

namespace App\Dtos;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class GatewayResponseDto
{
    private function __construct(
        public int   $code,
        public array $body,
        public array $headers
    )
    {
    }

    public static function createFromArray(array $data): GatewayResponseDto
    {
        if (
            !isset($data['code']) ||
            !is_int($data['code']) ||
            !isset($data['body']) ||
            !is_array($data['body']) ||
            !isset($data['headers']) ||
            !is_array($data['headers'])
        ) {
            return new self(
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                [],
                []
            );
        }

        return new self(
            $data['code'],
            $data['body'],
            $data['headers']
        );
    }
}
