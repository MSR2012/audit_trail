<?php

namespace App\Actions\Ips;

use App\Dtos\Ips\UpdateIpDto;
use App\Models\Ip;

class UpdateIp
{
    public function execute(Ip $ip, UpdateIpDto $updateIpDto): Ip
    {
        $ip->label = $updateIpDto->label;
        $ip->comment = $updateIpDto->comment;
        $ip->save();

        return $ip;
    }
}
