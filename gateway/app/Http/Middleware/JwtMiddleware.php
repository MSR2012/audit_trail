<?php namespace App\Http\Middleware;

use App\Services\Securities\DecoderInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class JwtMiddleware
{

    public function __construct(
        private readonly DecoderInterface $decoderService
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
        $tokenPayload = $this->decoderService->decode($token);
        if (
            !$tokenPayload ||
            Cache::has('blacklist_token_' . $tokenPayload['jti']) ||
            $tokenPayload['exp'] < Carbon::now()
        ) {
            return response()->json([
                'error_message' => 'Invalid access token.',
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $request->headers->set('AT-USER-ID', $tokenPayload['user_id']);
        $request->headers->set('AT-ROLE', $tokenPayload['role']);
        $request->headers->set('AT-JTI', $tokenPayload['jti']);

        return $next($request);
    }
}
