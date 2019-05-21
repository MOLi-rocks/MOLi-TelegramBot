<?php

namespace MOLiBot\Http\Middleware;

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
        $tokenService = resolve('MOLiBot\\Services\\MOLiBotApiTokenService');

        if ( $tokenService->checkTokenExist($request->header('Authorization')) ) {
            return $next($request);
        }

        return response()->json(['massages' => 'token_invalid or not provide'], 401);
    }
}
