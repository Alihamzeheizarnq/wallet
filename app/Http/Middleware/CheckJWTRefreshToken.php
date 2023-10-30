<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckJWTRefreshToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (
            $token = $request->headers->get('Authorization') and
            $token = str_replace('Bearer ', '', $token)
        ) {

            dd($token);
        }


        dd($token);

        return $next($request);
    }
}
