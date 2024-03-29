<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Headers', "Authorization,Accept,Content-Type,DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control")
            ->header('Access-Control-Allow-Methods', "PUT,POST,DELETE,GET,OPTIONS")
            ->header('Access-Control-Allow-Credentials', 'true');
    }
}