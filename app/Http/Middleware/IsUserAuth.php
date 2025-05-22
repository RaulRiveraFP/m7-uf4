<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class IsUserAuth
{
    public function handle($request, Closure $next)
    {
        try {
            // Comprova i autentica l'usuari a partir del token
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized: usuari no trobat'
                ], 401);
            }

        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Unauthorized: token invàlid o no proporcionat'
            ], 401);
        }

        return $next($request);
    }
}
