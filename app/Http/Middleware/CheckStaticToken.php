<?php

namespace App\Http\Middleware;

use Closure;

class CheckStaticToken
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

        $secret = '6PdVuGprmeSsdq0X3NJMm8tIfjwGC54Hrgntw8cIVpsBBQSs068ihcKV2Dnu8CNR1PSqBvM1ygrbaLP5Ez3kcluiik2huXip7XCl';

        if(!request()->hasHeader('secret') || request()->header('secret') != $secret){
            return response()->json(['message' => 'Unauthorized', 'status' => 401], 401);
        }
        return $next($request);
    }
}
