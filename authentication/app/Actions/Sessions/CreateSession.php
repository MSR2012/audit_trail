<?php

namespace App\Actions\Sessions;

use App\Dtos\CreateSessionDto;
use App\Models\Session;

class CreateSession
{
    public function execute(CreateSessionDto $createSessionDto): Session
    {
        $session = new Session();
        $session->user_id = $createSessionDto->user_id;
        $session->uuid = $createSessionDto->uuid;
        $session->ip_address = $createSessionDto->ip_address;
        $session->user_agent = $createSessionDto->user_agent;
        $session->token = $createSessionDto->token;
        $session->token_expires_at = $createSessionDto->token_expires_at;
        $session->refresh_token = $createSessionDto->refresh_token;
        $session->refresh_token_expires_at = $createSessionDto->refresh_token_expires_at;
        $session->save();

        return $session;
    }
}
