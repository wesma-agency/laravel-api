<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ApiBackend {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {


        $JWTAuth = false;

        try {
            $JWTAuth = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
            
            $accessData = $JWTAuth->getOriginal();

            $request->merge([
                'accessData' => $accessData
            ]);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return redirect('/');
        }
        catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return redirect('/');
        }
        catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
            return redirect('/');
        }

        if ($JWTAuth) {
            return $next($request);
        } else {
            return redirect('/');
        }

    }
}