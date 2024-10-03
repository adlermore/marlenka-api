<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWeb extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {

        return parent::handle($request, $next, $guards);
    }

    protected function redirectTo($request)
    {

        if (! $request->expectsJson()) {
            $lang = $request->getSession()->get('lang');
            return route('login');
        }
    }
}
