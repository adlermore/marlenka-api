<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use Illuminate\Http\Request;

class BlockCors extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next)
    {

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', '*')
            ->header('Access-Control-Allow-Credentials', true)
            ->header('Access-Control-Allow-Headers', 'X-Requested-With,Content-Type,X-Token-Auth,Authorization')
            ->header('Accept', 'application/json');
    }
}
