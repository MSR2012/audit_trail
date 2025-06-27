<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewByUser(
        int $changes_made_within = 1
    )
    {
        $auditLogs = AuditLog::all();

        return response()->json($auditLogs->toArray(), 200);
    }

    public function viewByIp(
        string $ip_address,
        int    $changes_made_within = 1
    )
    {
        return response()->json(['message' => 'All ips'], 200);
    }

    public function create(Request $request)
    {
        AuditLog::create([
            'user_id' => $request->header('at-user-id'),
            'jti' => $request->header('at-jti'),
            'ip_address' => $request->get('ip_address', ''),
            'action' => $request->get('action'),
            'changes' => $request->get('changes'),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return response()->json(['message' => 'Audit log created successfully'], 200);
    }
}
