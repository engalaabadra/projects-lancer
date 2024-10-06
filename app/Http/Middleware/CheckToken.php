<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class CheckToken
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
        $token = $request->header('token');
        $user = User::where('token', $token)->first();

        if(!$user){
            return response()->json(['message' => 'Unauthenticated', 'status' => 401], 401);
        } elseif(!$request->hasHeader('token') || $token != $user->api_token){
            return response()->json(['message' => 'Unauthenticated', 'status' => 401], 401);
        }

        return $next($request);
    }
}
