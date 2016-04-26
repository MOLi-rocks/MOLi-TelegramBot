<?php

namespace MOLiBot\Http\Middleware;

use Closure;
use Storage;

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
        $tokens = Storage::disk('local')->files('/api');
        
        foreach ($tokens as $val) {
            $token = explode("/", $val);
            if ($request->header('Authorization') == $token[1]) {
                return $next($request);
            }
        }
        return response()->json(['massages' => 'token_invalid'], 404);
    }
}
