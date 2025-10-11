<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleAndRTL
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session(
            'locale',
            $request->cookie(
                'locale',
                config('app.locale')
            )
        );

        App::setLocale($locale);

        // Set RTL based on locale
        $rtlLocales = ['ar'];
        view()->share('isRTL', in_array($locale, $rtlLocales));


        return $next($request);
    }
}