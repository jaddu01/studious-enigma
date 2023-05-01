<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseBuilder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $user = $request->user('api');
            if(!$user){
                return ResponseBuilder::error("Unauthorised", 401);
            }
        }catch (\Exception $e){
            Log::error($e);
            return ResponseBuilder::error($e->getMessage(), 500);
        }
        return $next($request);
    }
}
