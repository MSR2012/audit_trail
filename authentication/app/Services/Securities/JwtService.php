<?php

namespace App\Services\Securities;

use Illuminate\Support\Carbon;

class JwtService implements EncoderInterface
{
    private string $secretKey;
    private string $alg;
    private string $typ;

    private int $tokenLifetime;
    private int $refreshTokenLifetime;
    private string $issuer;

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET_KEY');
        $this->alg = env('JWT_ALG');
        $this->typ = env('JWT_TYP');
        $this->tokenLifetime = env('ACCESS_TOKEN_LIFETIME');
        $this->refreshTokenLifetime = env('REFRESH_TOKEN_LIFETIME');
        $this->issuer = env('APP_URL');
    }

    public function encode(array $payload, string $type = 'access'): array
    {
        $header = [
            'alg' => $this->alg,
            'typ' => $this->typ,
        ];
        $headerBase64Url = $this->base64UrlEncode(json_encode($header));

        $exp = Carbon::now()->addMinutes($type === 'access' ? $this->tokenLifetime : $this->refreshTokenLifetime);
        $payload = array_merge($payload, [
            'iss' => $this->issuer,
            'exp' => $exp,
        ]);
        $payloadBase64Url = $this->base64UrlEncode(json_encode($payload));
        $signature = $this->generateSignature($headerBase64Url, $payloadBase64Url);

        return [
            'token' => $headerBase64Url . '.' . $payloadBase64Url . '.' . $signature,
            'exp' => $exp,
        ];
    }

    private function generateSignature(string $header, string $payload): string
    {
        $signature = hash_hmac($this->alg, $header . '.' . $payload, $this->secretKey, true);

        return $this->base64UrlEncode($signature);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
