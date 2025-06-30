<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
            return response()->json([
                'error_message' => 'Unauthorized',
            ], ResponseAlias::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
