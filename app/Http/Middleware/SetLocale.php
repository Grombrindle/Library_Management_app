<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $validLocales = ['en', 'ar', 'fr', 'de', 'es', 'tr'];

        // Determine locale from session first, then cookie, then fallback
        $locale = session('locale');
        if (!$locale) {
            $locale = $request->cookie('locale');
        }

        if (!$locale || !in_array($locale, $validLocales)) {
            $locale = config('app.locale');
        }

        // Set application locale
        App::setLocale($locale);

        // Queue cookie if not set or different
        if ($request->cookie('locale') !== $locale) {
            cookie()->queue(cookie('locale', $locale, 60 * 24 * 365)); // 1 year
        }

        return $next($request);
    }
}
