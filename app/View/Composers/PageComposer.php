<?php

namespace App\View\Composers;

use App\Models\Page;
use Butschster\Head\Packages\Entities\OpenGraphPackage;
use Butschster\Head\Packages\Entities\TwitterCardPackage;
use Meta;
use Route;
use Str;

class PageComposer
{
    public function compose($view)
    {
        $action = Route::current()->getActionMethod();
        // if (Route::current()->getActionMethod() != 'index') return;

        $controller = class_basename(Route::current()->controller);
        $page_type = Str::snake(str_replace('Controller', '', $controller));

        $page = Page::active()->where('type', $page_type)->first();

        if (
            $page
            && $action == 'index'
            && !in_array(Route::currentRouteName(), [
                'equipments.coin',
                'crypto_calc.coin',
                'firmwares.category',
                'wiki.category',
                'news.category',
                'data_centers.country',
                'data_centers.city',
            ])
        ) {
            $page_number = request()->has('page') ? ' - ' . request()->input('page') . ' ' . __('common.page') : '';

            // Open graph
            $og = new OpenGraphPackage('og');
            $og->setType('website')
                ->setSiteName(env('APP_NAME', ''))
                ->setUrl(url()->current())
                ->addImage(asset('default/img/favicon.svg'))
                ->setTitle(htmlentities(($page->meta_title ?: $page->title)))
                ->setDescription(htmlentities($page->meta_description));

            // Twitter Card
            $tw = new TwitterCardPackage('tw');
            $tw->setType('summary')
                ->setImage(asset('default/img/favicon.svg'))
                ->setTitle(htmlentities(($page->meta_title ?: $page->title)))
                ->setDescription(htmlentities($page->meta_description));

            if ($twitter = parse_url(setting('twitter'), PHP_URL_PATH)) {
                $twitter = trim($twitter, '/');
                $tw->setSite("@$twitter");
                $tw->setCreator("@$twitter");
            }

            Meta::registerPackage($og);
            Meta::registerPackage($tw);

            // Metadata
            Meta::setTitle(($page->meta_title ?: $page->title) . $page_number);
            Meta::setDescription($page->meta_description . $page_number);

            $view->with([
                'page_title' => $page->title,
                'page_subtitle' => $page->subtitle,
                'page_description' => $page->description,
            ]);
        }
    }
}
