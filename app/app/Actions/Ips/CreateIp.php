<?php

namespace App\Actions\Ips;

use App\Dtos\Ips\CreateIpDto;
use App\Models\Ip;

class CreateIp
{
    public function execute(CreateIpDto $createIpDto): Ip
    {
        $ip = new Ip();
        $ip->user_id = $createIpDto->userId;
        $ip->ip_address = $createIpDto->ipAddress;
        $ip->label = $createIpDto->label;
        $ip->comment = $createIpDto->comment;
        $ip->save();

        return $ip;
    }
}
