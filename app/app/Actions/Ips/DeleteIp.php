<?php

namespace App\Actions\Ips;

use App\Models\Ip;

class DeleteIp
{
    public function execute(Ip $ip): void
    {
        $ip->delete();
    }
}
