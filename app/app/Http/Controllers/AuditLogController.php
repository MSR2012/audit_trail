<?php

namespace App\Http\Controllers;

use App\Constants\ChangesWithinType;
use App\Constants\Role;
use App\Services\Audits\AuditLogServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuditLogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private readonly AuditLogServiceInterface $auditLogService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->auditLogService->index(
                $this->isAdmin($request->headers->get('at-role')) ?
                    null :
                    $request->headers->get('at-user-id')
            ), ResponseAlias::HTTP_OK);
    }

    public function viewByLoggedInUser(Request $request, int $changes_within): JsonResponse
    {
        $userId = $request->headers->get('at-user-id');
        $sessionId = null;
        if ($changes_within === ChangesWithinType::SESSION) {
            $sessionId = $request->headers->get('at-jti');
        }

        return response()->json(
            $this->auditLogService->index(
                $userId,
                null,
                $sessionId
            ), ResponseAlias::HTTP_OK);
    }

    public function viewByUser(Request $request, int $user_id, int $changes_within): JsonResponse
    {
        $userId = $user_id;
        $sessionId = null;
        if (!$this->isAdmin($request->headers->get('at-role'))) {
            $userId = $request->headers->get('at-user-id');
        }
        if ($changes_within === ChangesWithinType::SESSION) {
            $sessionId = $request->headers->get('at-jti');
        }

        return response()->json(
            $this->auditLogService->index(
                $userId,
                null,
                $sessionId
            ), ResponseAlias::HTTP_OK);
    }

    public function viewByIp(Request $request, int $ip_address, int $changes_within): JsonResponse
    {
        $userId = null;
        $sessionId = null;
        if (!$this->isAdmin($request->headers->get('at-role'))) {
            $userId = $request->headers->get('at-user-id');
        }
        if ($changes_within === ChangesWithinType::SESSION) {
            $sessionId = $request->headers->get('at-jti');
        }

        return response()->json(
            $this->auditLogService->index(
                $userId,
                $ip_address,
                $sessionId
            ), ResponseAlias::HTTP_OK);
    }

    public function create(Request $request): JsonResponse
    {
        $this->auditLogService->create([
            'user_id' => $request->header('at-user-id'),
            'jti' => $request->header('at-jti'),
            'ip_address' => $request->get('ip_address', ''),
            'action' => $request->get('action'),
            'changes' => $request->get('changes'),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return response()->json(['message' => 'Audit log created successfully'], ResponseAlias::HTTP_OK);
    }

    private function isAdmin(?int $role): bool
    {
        return $role != Role::ADMIN;
    }
}
