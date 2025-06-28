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
        if (
            $request->headers->get('at-role') != Role::ADMIN &&
            !$this->ipRepository->get($request->route('id'))
        ) {
            return response()->json([
                'message' => 'Unauthorized',
            ], ResponseAlias::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
