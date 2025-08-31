<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApplyUserPreferences
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // $langs = $request->header('accept-language');
        // // dd($langs);
        // $locale = substr($langs,0,2);
        // $user = Auth::user();
        // if($user)
        // {
        //     App::setLocale($user->profile->locale);
        // }else{
        //     App::setLocale($locale);
        // }
        // return $next($request); 

        $supported = ['ar','en'];
        $header = $request->header('accept-language');
        $locales = explode(',',$header);
        $locale = config('app.locale');
        if($locales){
        foreach($locales as $locale)
        {
            if(($i =strpos($locale,';')) !== false ){
            $locale = substr($locale,0,$i);
            }

            if(in_array($locale,$supported)){
                // $locale = config('app.locale');
                break;
            }
        }
    }
        $user = Auth::user();
        if ($user) {
            $locale= $user->profile->locale ?? $locale;
        }

        app()->setLocale($locale);

        return $next($request); 
    
    }
}
