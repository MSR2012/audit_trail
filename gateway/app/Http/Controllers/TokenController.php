<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class TokenController extends Controller
{
    public function putInBlacklist(Request $request): JsonResponse
    {
        $jti = $request->get('jti');
        $expiresAt = $request->get('exp');
        Cache::put(
            'blacklist_token_' . $jti,
            $jti,
            Carbon::parse($expiresAt)->addHour()
        );

        return response()->json();
    }
}
