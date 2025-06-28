<?php namespace App\Http\Middleware;

use App\Constants\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CanDeleteIpAddress
{
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
            $request->headers->get('at-role') != Role::ADMIN
        ) {
            return response()->json([
                'message' => 'Unauthorized',
            ], ResponseAlias::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
