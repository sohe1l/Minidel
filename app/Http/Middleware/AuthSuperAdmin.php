<?php

namespace App\Http\Middleware;

use Closure;

class AuthSuperAdmin
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
        $admins = [1];
        
        if(!$request->user()  || !in_array($request->user()->id, $admins)){
            
            abort(403);

        }

        return $next($request);
    }
}
