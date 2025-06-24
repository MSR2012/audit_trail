<?php namespace App\Http\Middleware;

use App\Repositories\SessionRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class JwtMiddleware
{

    public function __construct(
        private readonly SessionRepository $sessionRepository
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
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json([
                'error_message' => 'Access token is missing.',
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $token = str_replace('Bearer ', '', $token);
        $session = $this->sessionRepository->getByToken($token);
        if (!$session || $session->token_expires_at < Carbon::now()) {
            return response()->json([
                'error_message' => 'Invalid access token.',
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
