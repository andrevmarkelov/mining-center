<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomRedirectUrl
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
		$redirect_list = [];

		function removeSlash($url) {
			$url = str_replace(env('APP_URL'), '', trim($url));
			$url = str_replace('/' . app()->getLocale(), '', $url);
			return rtrim(ltrim($url, '/'), '/');
		}

		if (!empty($redirect = setting('redirect'))) {
			foreach (explode(PHP_EOL, $redirect) as $key => $item) {
				$line = explode('|', $item);

				if (count($line) == 2) {
					$redirect_list[removeSlash($line[0])] = removeSlash($line[1]);
				}
			}
		}

		$current_url = removeSlash(url()->current());

		if (!empty($redirect_list[$current_url])) {
			$new_url = $redirect_list[$current_url];

			if ($new_url != $current_url) {
				return redirect(\LaravelLocalization::localizeUrl($new_url), 301);
			}
		}

		return $next($request);
	}
}
