<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class TokenController extends Controller
{
    private int $lifetime;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->lifetime = env('ACCESS_TOKEN_LIFETIME');
    }

    public function putInBlacklist(Request $request): JsonResponse
    {
        $jti = $request->get('jti');
        Cache::put(
            'blacklist_token_' . $jti,
            $jti,
            Carbon::now()->addMinutes($this->lifetime)
        );

        return response()->json();
    }
}
