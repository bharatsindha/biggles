<?php

namespace App\Http\Middleware;

use Closure;

class VerifyAPIToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->header('x-api-key')){
            // Receiving data from header
            $authorizationKey = $request->header('x-api-key');

            if (env('API_AUTHORIZATION_KEY') != $authorizationKey) {

                return response()->json(["message" => 'Invalid authorization key.'], 401);
            }

            return $next($request);
        }

        return response()->json(['message' => 'Not a valid API request.'], 401);
    }
}
