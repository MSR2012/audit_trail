<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuthenticatedRequest
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
            !$request->headers->has('at-user-id') ||
            !$request->headers->has('at-role') ||
            !$request->headers->has('at-jti')
        ) {
            return response('Forbidden', 403);
        }

        return $next($request);
    }
}
