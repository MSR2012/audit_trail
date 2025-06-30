<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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

        $origins = explode(',', $request->headers->get('Origin'));
        foreach ($origins as $origin) {
            $origin = trim($origin);
            if ($origin && in_array($origin, $allowedDomains)) {
                return $next($request);
            }
        }

        return response()->json([
            'error_message' => 'Unauthorized',
        ], ResponseAlias::HTTP_FORBIDDEN);
    }
}
