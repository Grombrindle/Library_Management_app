<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetRTL
{
    public function handle(Request $request, Closure $next): Response
    {
        $rtlLocales = ['ar']; // Add more RTL locales if needed
        
        if (in_array(App::getLocale(), $rtlLocales)) {
            view()->share('isRTL', true);
        } else {
            view()->share('isRTL', false);
        }

        return $next($request);
    }
} 