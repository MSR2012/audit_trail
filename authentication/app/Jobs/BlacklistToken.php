<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BlacklistToken extends Job
{
    private string $baseUrl;
    private string $jti;
    private string $expiresAt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $jti, string $expires_at)
    {
        $this->baseUrl = env('AT_GATEWAY_BASE_URL');
        $this->jti = $jti;
        $this->expiresAt = $expires_at;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle(): void
    {
        (new Client())->get(
            $this->baseUrl . '/blacklist_token?jti=' . $this->jti . '&exp=' . $this->expiresAt
        );
    }
}
