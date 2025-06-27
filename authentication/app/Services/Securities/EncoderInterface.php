<?php

namespace App\Services\Securities;

interface EncoderInterface
{
    public function encode(array $payload, string $type): array;
}
