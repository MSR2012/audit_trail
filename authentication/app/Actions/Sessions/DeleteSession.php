<?php

namespace App\Actions\Sessions;

use App\Models\Session;

class DeleteSession
{
    public function execute(Session $session): void
    {
        $session->delete();
    }
}
