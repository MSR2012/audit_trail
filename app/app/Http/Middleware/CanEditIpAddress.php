<?php

namespace App\Http\Middleware;

use App\Constants\Role;
use App\Repositories\IpRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CanEditIpAddress
{
    public function __construct(private readonly IpRepository $ipRepository)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $ip = $this->ipRepository->get($request->route('id'));
        if (
            $request->headers->get('at-role') != Role::ADMIN &&
            (
                !$ip ||
                $ip->user_id != $request->headers->get('at-user-id')
            )
        ) {
            return response()->json([
                'error_message' => 'Unauthorized',
            ], ResponseAlias::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
