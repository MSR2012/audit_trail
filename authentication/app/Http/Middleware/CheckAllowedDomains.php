<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAllowedDomains
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
        $allowedDomains = [
            env('AT_GATEWAY_BASE_URL'),
        ];

        $origin = $request->headers->get('Origin');
        if (!$origin || !in_array($origin, $allowedDomains)) {
            return response('Forbidden', 403);
        }

        return $next($request);
    }
}
