<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BlacklistToken extends Job
{
    private string $jti;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $jti)
    {
        $this->jti = $jti;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle(): void
    {
        (new Client())->get(env('AT_GATEWAY_BASE_URL') . '/blacklist_token?jti=' . $this->jti);
    }
}
