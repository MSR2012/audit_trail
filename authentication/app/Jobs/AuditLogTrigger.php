<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class AuditLogTrigger extends Job
{
    private string $baseUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly array  $headers,
        private readonly int    $action,
        private readonly string $message
    )
    {
        $this->baseUrl = rtrim(env('AT_APP_BASE_URL'), '/');
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle(): void
    {
        (new Client())->request(
            'POST',
            $this->baseUrl . '/app/audit_log/create',
            [
                'headers' => $this->headers,
                'form_params' => [
                    'action' => $this->action,
                    'changes' => $this->message,
                ],
            ]
        );
    }
}
