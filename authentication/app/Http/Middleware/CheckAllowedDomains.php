<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckAllowedDomains
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowedDomains = [
            env('AT_GATEWAY_BASE_URL'),
        ];

        $origin = $request->headers->get('Origin');
        Log::info($origin);
        if (!$origin || !in_array($origin, $allowedDomains)) {
            return response('Forbidden', 403);
        }

        return $next($request);
    }
}
