<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if($request->expectsJson()){
            return null;
        }else{
            throw new HttpResponseException(response()->json([
                'success' => false,
                'data'    => [],
                'message' => 'Unauthorized. You must be authenticated to access this resource.',
            ], 401));
        }
    }
}
