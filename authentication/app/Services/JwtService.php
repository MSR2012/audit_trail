<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class JwtService
{
    private string $secretKey;
    private string $alg;
    private string $typ;

    private int $tokenLifetime;
    private int $refreshTokenLifetime;

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET_KEY');
        $this->alg = env('JWT_ALG');
        $this->typ = env('JWT_TYP');
        $this->tokenLifetime = env('ACCESS_TOKEN_LIFETIME');
        $this->refreshTokenLifetime = env('REFRESH_TOKEN_LIFETIME');
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
            'iss' => env('APP_URL'),
            'exp' => $exp,
        ]);
        $payloadBase64Url = $this->base64UrlEncode(json_encode($payload));
        $signature = $this->generateSignature($headerBase64Url, $payloadBase64Url);

        return [
            'token' => $headerBase64Url . '.' . $payloadBase64Url . '.' . $signature,
            'exp' => $exp,
        ];
    }

    public function decode(string $token): ?array
    {
        $tokens = explode('.', $token);
        if (count($tokens) !== 3) {
            return null;
        }
        list($headerBase64Url, $payloadBase64Url, $signature) = $tokens;
        if (
            !$this->validateHeader($headerBase64Url) ||
            !$this->validateSignature($headerBase64Url, $payloadBase64Url, $signature)
        ) {
            return null;
        }

        return json_decode($this->base64UrlDecode($payloadBase64Url), true);
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

    private function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    private function validateHeader(string $headerBase64Url): bool
    {
        $header = json_decode($this->base64UrlDecode($headerBase64Url), true);
        if (
            isset($header['alg']) &&
            $header['alg'] === $this->alg &&
            isset($header['typ']) &&
            $header['typ'] === $this->typ
        ) {
            return true;
        }

        return false;
    }

    private function validateSignature(
        string $headerBase64Url,
        string $payloadBase64Url,
        string $signature
    ): bool
    {
        $expectedSignature = $this->generateSignature($headerBase64Url, $payloadBase64Url);

        return hash_equals($signature, $expectedSignature);
    }
}
