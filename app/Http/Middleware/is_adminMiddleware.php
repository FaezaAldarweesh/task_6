<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class is_adminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //check if the user have admin role or not
        if(Auth::user()->role == 'admin'){
            return $next($request);
        }

        //if not : delete the token and return massage response
        auth()->user()->tokens()->delete();

        return response()->json(['error' => 'you do not have permission to do that.'], 403);
    }
}
