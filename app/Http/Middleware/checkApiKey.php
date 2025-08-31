<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response;

class checkApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('x-api-key');
        // dd($key , config('services.api_key'));251
        if(!$key || $key != config('services.api_key') )
        {
            return FacadesResponse::json([
                'message' => 'Missing or invalid Api Key',
            ],400);
        }
        return $next($request);
    }
}
