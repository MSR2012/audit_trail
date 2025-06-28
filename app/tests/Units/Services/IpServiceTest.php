<?php

namespace Tests\Units\Services;

use App\Actions\Ips\CreateIp;
use App\Actions\Ips\DeleteIp;
use App\Actions\Ips\UpdateIp;
use App\Exceptions\DuplicateRecordException;
use App\Models\Ip;
use App\Repositories\IpRepository;
use App\Services\Ips\IpService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Str;
use PHPUnit\Framework\MockObject\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;

class IpServiceTest extends TestCase
{
    private IpRepository $ipRepository;
    private CreateIp $createIp;
    private UpdateIp $updateIp;
    private DeleteIp $deleteIp;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->ipRepository = $this->createMock(IpRepository::class);
        $this->createIp = $this->createMock(CreateIp::class);
        $this->updateIp = $this->createMock(UpdateIp::class);
        $this->deleteIp = $this->createMock(DeleteIp::class);
    }

    /**
     * @dataProvider defaultDataProvider
     * @return void
     * @throws Exception
     */
    public function testList(array $ips)
    {
        $this->ipRepository->method('all')->willReturn($this->getIps($ips));
        $ipService = app(IpService::class, [
            'ipRepository' => $this->ipRepository,
            'createIp' => $this->createIp,
            'updateIp' => $this->updateIp,
            'deleteIp' => $this->deleteIp,
        ]);

        $this->assertSameSize($ips, $ipService->list());
    }

    /**
     * @dataProvider defaultDataProvider
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RecordsNotFoundException
     */
    public function testGet(array $ips)
    {
        $this->ipRepository->method('get')->willReturn(null);
        $ipService = app(IpService::class, [
            'ipRepository' => $this->ipRepository,
            'createIp' => $this->createIp,
            'updateIp' => $this->updateIp,
            'deleteIp' => $this->deleteIp,
        ]);

        $this->expectException(RecordsNotFoundException::class);
        $ipService->get('1232434342');

        $ip = $this->castArrayToIp($ips[0]);
        $this->ipRepository->method('get')->willReturn($ip);
        $ipService = app(IpService::class, [
            'ipRepository' => $this->ipRepository,
            'createIp' => $this->createIp,
            'updateIp' => $this->updateIp,
            'deleteIp' => $this->deleteIp,
        ]);

        $this->assertEquals($ips[0]['ip_address'], $ipService->get($ip->id)['ip_address']);
    }

    /**
     * @dataProvider defaultDataProvider
     * @return void
     */
    public function testStore(array $ips)
    {
        $ip = $this->castArrayToIp($ips[0]);
        $this->ipRepository->method('getByIpAddress')->willReturn($ip);
        $ipService = app(IpService::class, [
            'ipRepository' => $this->ipRepository,
            'createIp' => $this->createIp,
            'updateIp' => $this->updateIp,
            'deleteIp' => $this->deleteIp,
        ]);

        $this->expectException(DuplicateRecordException::class);
        $ipService->store($ip->user_id, $ip->ip_address, $ip->label, $ip->comment);

        $ip = $this->castArrayToIp($ips[0]);
        $this->ipRepository->method('getByIpAddress')->willReturn(null);
        $this->createIp->method('execute')->willReturn($ip);
        $ipService = app(IpService::class, [
            'ipRepository' => $this->ipRepository,
            'createIp' => $this->createIp,
            'updateIp' => $this->updateIp,
            'deleteIp' => $this->deleteIp,
        ]);

        $ip = $ipService->store($ip->user_id, $ip->ip_address, $ip->label, $ip->comment);
        $this->assertEquals($ips[0]['ip_address'], $ip['ip_address']);
    }

    /**
     * @dataProvider defaultDataProvider
     * @return void
     */
    public function testUpdate(array $ips)
    {
        $ip = $this->castArrayToIp($ips[0]);
        $this->ipRepository->method('get')->willReturn(null);
        $ipService = app(IpService::class, [
            'ipRepository' => $this->ipRepository,
            'createIp' => $this->createIp,
            'updateIp' => $this->updateIp,
            'deleteIp' => $this->deleteIp,
        ]);

        $this->expectException(RecordsNotFoundException::class);
        $ipService->update($ip->id, $ip->label, $ip->comment);

        $ip = $this->castArrayToIp($ips[0]);
        $this->ipRepository->method('get')->willReturn($ip);
        $this->updateIp->method('execute')->willReturn($ip);
        $ipService = app(IpService::class, [
            'ipRepository' => $this->ipRepository,
            'createIp' => $this->createIp,
            'updateIp' => $this->updateIp,
            'deleteIp' => $this->deleteIp,
        ]);

        $ip = $ipService->update($ip->id, $ip->label, $ip->comment);
        $this->assertEquals($ips[0]['ip_address'], $ip['ip_address']);
    }

    /**
     * @dataProvider defaultDataProvider
     * @return void
     */
    public function testDelete(array $ips)
    {
        $ip = $this->castArrayToIp($ips[0]);
        $this->ipRepository->method('get')->willReturn(null);
        $ipService = app(IpService::class, [
            'ipRepository' => $this->ipRepository,
            'createIp' => $this->createIp,
            'updateIp' => $this->updateIp,
            'deleteIp' => $this->deleteIp,
        ]);

        $this->expectException(RecordsNotFoundException::class);
        $ipService->delete($ip->id, $ip->label, $ip->comment);

        $ip = $this->castArrayToIp($ips[0]);
        $this->ipRepository->method('get')->willReturn($ip);
        $ipService = app(IpService::class, [
            'ipRepository' => $this->ipRepository,
            'createIp' => $this->createIp,
            'updateIp' => $this->updateIp,
            'deleteIp' => $this->deleteIp,
        ]);

        $ipService->delete($ip->id);
        $this->assertTrue(true);
    }

    private function getIps(array $ips): Collection
    {
        $ipCollections = new Collection();
        foreach ($ips as $ip) {
            $ipCollections->add($this->castArrayToIp($ip));
        }

        return $ipCollections;
    }

    private function castArrayToIp(array $data): Ip
    {
        $ipNew = new Ip();
        $ipNew->id = Str::random();
        $ipNew->user_id = $data['user_id'];
        $ipNew->ip_address = $data['ip_address'];
        $ipNew->label = $data['label'];
        $ipNew->comment = $data['comment'];

        return $ipNew;
    }

    /**
     * @return array[]
     */
    public static function defaultDataProvider(): array
    {
        $sampleIpData = self::sampleIpData();

        return [
            [
                [
                    $sampleIpData,
                    array_merge(['ip_address' => '127.0.0.2'], $sampleIpData),
                    array_merge(['user_id' => 2, 'ip_address' => '127.0.0.3'], $sampleIpData),
                ],
            ],
        ];
    }

    private static function sampleIpData(): array
    {
        return [
            'user_id' => 1,
            'ip_address' => '127.0.0.1',
            'label' => 'localhost',
            'comment' => 'Some comment',
        ];
    }
}
