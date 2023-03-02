<?php

namespace App\Http\Controllers;

use Meta;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Butschster\Head\Packages\Entities\OpenGraphPackage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LaravelLocalization;
use Butschster\Head\Packages\Entities\TwitterCardPackage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function openGraph($model)
    {
        // Open Graph
        $og = new OpenGraphPackage('og');
        $og->setType('website')
            ->setLocale(LaravelLocalization::getCurrentLocaleRegional())
            ->setSiteName(env('APP_NAME', ''))
            ->setUrl(url()->current())
            ->setTitle(htmlentities(($model->meta_title ?: $model->title)))
            ->setDescription(htmlentities($model->meta_description));

        if ($model->image) {
            $og->addImage(asset($model->image));
        }

        // Twitter Card
        $tw = new TwitterCardPackage('tw');
        $tw->setType('summary')
            ->setTitle(htmlentities(($model->meta_title ?: $model->title)))
            ->setDescription(htmlentities($model->meta_description));

        if ($model->image) {
            $tw->setImage(asset($model->image));
        }

        if ($twitter = parse_url(setting('twitter'), PHP_URL_PATH)) {
            $twitter = trim($twitter, '/');
            $tw->setSite("@$twitter");
            $tw->setCreator("@$twitter");
        }

        Meta::registerPackage($og);
        Meta::registerPackage($tw);
    }
}
