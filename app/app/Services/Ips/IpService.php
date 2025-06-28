<?php

namespace App\Services\Ips;

use App\Actions\Ips\CreateIp;
use App\Actions\Ips\DeleteIp;
use App\Actions\Ips\UpdateIp;
use App\Dtos\Ips\CreateIpDto;
use App\Dtos\Ips\UpdateIpDto;
use App\Exceptions\DuplicateRecordException;
use App\Models\Ip;
use App\Repositories\IpRepository;
use Illuminate\Database\RecordsNotFoundException;

class IpService implements IpServiceInterface
{
    public function __construct(
        private readonly IpRepository $ipRepository,
        private readonly CreateIp     $createIp,
        private readonly UpdateIp     $updateIp,
        private readonly DeleteIp     $deleteIp
    )
    {
    }

    public function list(): array
    {
        $ips = $this->ipRepository->all();

        return $ips->map(function (Ip $ip) {
            return [
                'id' => $ip->id,
                'user_id' => $ip->user_id,
                'ip_address' => $ip->ip_address,
                'label' => $ip->label,
                'comment' => $ip->comment,
            ];
        })->toArray();
    }

    /**
     * @throws RecordsNotFoundException
     */
    public function get(string $id): array
    {
        $ip = $this->ipRepository->get($id);
        if (!$ip) {
            throw new RecordsNotFoundException('Ip not found.');
        }

        return [
            'id' => $ip->id,
            'user_id' => $ip->user_id,
            'ip_address' => $ip->ip_address,
            'label' => $ip->label,
            'comment' => $ip->comment,
        ];
    }

    /**
     * @throws DuplicateRecordException
     */
    public function store(int $userId, string $ipAddress, string $label, string $comment): array
    {
        if (!empty($this->ipRepository->getByIpAddress($ipAddress))) {
            throw new DuplicateRecordException('Ip address already exists.');
        }

        $ip = $this->createIp->execute(CreateIpDto::createFromArray([
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'label' => $label,
            'comment' => $comment,
        ]));

        return [
            'id' => $ip->id,
            'user_id' => $ip->user_id,
            'ip_address' => $ip->ip_address,
            'label' => $ip->label,
            'comment' => $ip->comment,
        ];
    }

    /**
     * @throws RecordsNotFoundException
     */
    public function update(string $id, string $label, string $comment): array
    {
        $ip = $this->ipRepository->get($id);
        if (!$ip) {
            throw new RecordsNotFoundException('Ip not found.');
        }

        $ip = $this->updateIp->execute($ip, UpdateIpDto::createFromArray([
            'label' => $label,
            'comment' => $comment,
        ]));

        return [
            'id' => $ip->id,
            'user_id' => $ip->user_id,
            'ip_address' => $ip->ip_address,
            'label' => $ip->label,
            'comment' => $ip->comment,
        ];
    }

    /**
     * @throws RecordsNotFoundException
     */
    public function delete(string $id): void
    {
        $ip = $this->ipRepository->get($id);
        if (!$ip) {
            throw new RecordsNotFoundException('Ip not found.');
        }

        $this->deleteIp->execute($ip);
    }
}
