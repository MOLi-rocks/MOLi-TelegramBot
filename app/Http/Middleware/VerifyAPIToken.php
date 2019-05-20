<?php

namespace MOLiBot\Http\Middleware;

use Closure;
use MOLiBot\Models\MOLi_Bot_API_TOKEN;

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
        if ( MOLi_Bot_API_TOKEN::where('token', $request->header('Authorization'))->exists() ) {
            return $next($request);
        }

        return response()->json(['massages' => 'token_invalid or not provide'], 401);
    }
}
