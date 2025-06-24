<?php namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class JwtRefreshMiddleware
{

    public function __construct(
        private readonly JwtService $jwtService
    )
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
        $token = $request->header('Refresh-Token');
        if (!$token) {
            return response()->json([
                'error_message' => 'Refresh token is missing.',
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $refreshTokenPayload = $this->jwtService->decode($token);
        if (!$refreshTokenPayload || $refreshTokenPayload['exp'] < Carbon::now()) {
            return response()->json([
                'error_message' => 'Invalid refresh token.',
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
