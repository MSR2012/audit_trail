<?php

namespace App\Actions\Sessions;

use App\Dtos\UpdateSessionDto;
use App\Models\Session;

class UpdateSession
{
    public function execute(Session $session, UpdateSessionDto $updateSessionDto): Session
    {
        $session->token = $updateSessionDto->token;
        $session->token_expires_at = $updateSessionDto->token_expires_at;
        $session->save();

        return $session;
    }
}
