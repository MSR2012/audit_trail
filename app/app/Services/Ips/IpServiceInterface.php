<?php

namespace App\Services\Ips;

use App\Exceptions\DuplicateRecordException;
use Illuminate\Database\RecordsNotFoundException;

interface IpServiceInterface
{
    public function list(): array;

    /**
     * @throws RecordsNotFoundException
     */
    public function get(string $id): array;

    /**
     * @throws DuplicateRecordException
     */
    public function store(int $userId, string $ipAddress, string $label, string $comment): array;

    /**
     * @throws RecordsNotFoundException
     */
    public function update(string $id, string $label, string $comment): array;

    /**
     * @throws RecordsNotFoundException
     */
    public function delete(string $id): void;
}
