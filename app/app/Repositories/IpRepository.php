<?php

namespace App\Repositories;

use App\Models\Ip;
use Illuminate\Database\Eloquent\Collection;

class IpRepository
{
    public function __construct(
        private readonly Ip $ip
    )
    {
    }

    public function all(): Collection
    {
        return $this->ip->all();
    }

    public function get(string $id): ?Ip
    {
        return $this->ip->where('id', $id)->first();
    }

    public function getByIpAddress(string $ipAddress): ?Ip
    {
        return $this->ip->where('ip_address', $ipAddress)->first();
    }
}
