<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supportedLocales = ['id', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        // Check query param first (one-time switch via /lang/{locale})
        $locale = Session::get('locale', config('app.locale', 'id'));

        // Ensure it's a supported locale
        if (! in_array($locale, $this->supportedLocales)) {
            $locale = 'id';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
