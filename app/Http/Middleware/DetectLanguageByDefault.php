<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use LaravelLocalization;

class DetectLanguageByDefault
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
        $accept_lang    = array_keys(LaravelLocalization::getSupportedLocales());
        $user_main_lang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : '';

        if (
            in_array($user_main_lang, $accept_lang)
            && app()->getLocale() != $user_main_lang
            && !session('language')
        ) {
            session(['language' => $user_main_lang]);

            return redirect(LaravelLocalization::getLocalizedURL($user_main_lang));
        }

        return $next($request);
    }
}
