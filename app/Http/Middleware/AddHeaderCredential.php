<?php

namespace App\Http\Middleware;

use Closure;

class AddHeaderCredential
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
        if ($request->has('access_token') && !$request->headers->has('Authorization')) {
            $request->headers->set('Authorization', 'Bearer ' . $request->get('access_token'));
        }

        //additional to handle to pass from param to header
        
        return $next($request);
    }
}