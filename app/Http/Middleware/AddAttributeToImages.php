<?php

namespace App\Http\Middleware;

use Closure;

class AddAttributeToImages
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
        $response = $next($request);

        $html = $response->getContent();
        $html = preg_replace('!<img(.*?)-(\d{1,5})x(\d{1,5})(.[^\"\d]*?)"!', '<img$1-$2x$3$4" width=$2 height=$3', $html);
        $html = str_ireplace([' loading="lazy"', ' decoding="async"'], '', $html);
        $html = preg_replace('!<img([^>]*)src=([^>]*)>!ix', '<img loading=lazy$1src=$2>', $html);
        $html = str_replace('<iframe', '<iframe loading="lazy"', $html);
        $html = preg_replace('/<img loading="?lazy"? not-lazy/', '<img', $html);

        return $response->setContent($html);
    }
}
