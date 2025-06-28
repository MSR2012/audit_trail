<?php

namespace Tests\Units\Services;

use App\Actions\AuditLogs\CreateLog;
use App\Models\AuditLog;
use App\Repositories\AuditLogRepository;
use App\Services\Audits\AuditLogService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class AuditLogServiceTest extends TestCase
{
    private AuditLogRepository $auditLogRepository;
    private CreateLog $createLog;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->auditLogRepository = $this->createMock(AuditLogRepository::class);
        $this->createLog = $this->createMock(CreateLog::class);
    }

    /**
     * @dataProvider defaultDataProvider
     * @return void
     * @throws Exception
     */
    public function testIndex(array $auditLogs)
    {
        $this->auditLogRepository->method('all')->willReturn($this->getAuditLogs($auditLogs));
        $auditLogService = app(AuditLogService::class, [
            'auditLogRepository' => $this->auditLogRepository,
            'createLog' => $this->createLog,
        ]);

        $this->assertSameSize($auditLogs, $auditLogService->index());
    }

    /**
     * @dataProvider defaultDataProvider
     * @return void
     */
    public function testCreate(array $auditLogs)
    {
        $auditLog = $this->castArrayToAuditLog($auditLogs[0]);
        $this->createLog->method('execute')->willReturn($auditLog);
        $auditLogService = app(AuditLogService::class, [
            'auditLogRepository' => $this->auditLogRepository,
            'createLog' => $this->createLog,
        ]);

        $this->assertEquals($auditLog->ip_address, $auditLogService->create($auditLogs[0])['ip_address']);
    }

    private function getAuditLogs(array $auditLogs): Collection
    {
        $auditLogCollections = new Collection();
        foreach ($auditLogs as $auditLog) {
            $auditLogCollections->add($this->castArrayToAuditLog($auditLog));
        }

        return $auditLogCollections;
    }

    private function castArrayToAuditLog(array $data): AuditLog
    {
        $auditLog = new AuditLog();
        $auditLog->id = Str::random();
        $auditLog->user_id = $data['user_id'];
        $auditLog->jti = $data['jti'];
        $auditLog->ip_address = $data['ip_address'];
        $auditLog->action = $data['action'];
        $auditLog->changes = $data['changes'];
        $auditLog->ip = $data['changes'];
        $auditLog->user_agent = $data['user_agent'];

        return $auditLog;
    }

    /**
     * @return array[]
     */
    public static function defaultDataProvider(): array
    {
        $sampleAuditLogData = self::sampleAuditLogData();

        return [
            [
                [
                    $sampleAuditLogData,
                    $sampleAuditLogData,
                    $sampleAuditLogData,
                ],
            ],
        ];
    }

    private static function sampleAuditLogData(): array
    {
        return [
            'user_id' => 1,
            'jti' => Str::random(),
            'ip_address' => '127.0.0.1',
            'action' => 3,
            'changes' => 'Some changes',
            'ip' => '127.0.0.1',
            'user_agent' => 'Postman',
        ];
    }
}
