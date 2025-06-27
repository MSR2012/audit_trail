<?php

namespace App\Services\Securities;

interface DecoderInterface
{
    public function decode(string $token): ?array;
}
