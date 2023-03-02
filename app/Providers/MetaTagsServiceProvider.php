<?php

namespace App\Providers;

use App\Services\ExcludedPostService;
use Butschster\Head\Facades\PackageManager;
use Butschster\Head\MetaTags\Meta;
use Butschster\Head\Contracts\MetaTags\MetaInterface;
use Butschster\Head\Contracts\Packages\ManagerInterface;
use Butschster\Head\Providers\MetaTagsApplicationServiceProvider as ServiceProvider;
use LaravelLocalization;

class MetaTagsServiceProvider extends ServiceProvider
{
    protected function packages()
    {
        PackageManager::create('select2', function ($package) {
            $package->addScript(
                'select2.js',
                '/admin/libs/select2/js/select2.full.min.js',
                ['defer']
            )->addStyle(
                'select2.css',
                '/admin/libs/select2/css/select2.min.css',
                ['rel' => 'stylesheet preload', 'as' => 'style']
            )->addStyle(
                'select2-bootstrap4.css',
                '/admin/libs/select2-bootstrap4-theme/select2-bootstrap4.min.css',
                ['rel' => 'stylesheet preload', 'as' => 'style']
            );
        });

        PackageManager::create('nouislider', function($package) {
            $package->addScript(
                'nouislider.js',
                '/default/libs/nouislider/nouislider.js',
                ['defer']
            )->addStyle(
                'nouislider.css',
                '/default/libs/nouislider/nouislider.css',
                ['rel' => 'stylesheet preload', 'as' => 'style']
            );
        });

        PackageManager::create('datatables', function($package) {
            $package->addScript(
                'dataTables.js',
                '/admin/libs/datatables/jquery.dataTables.min.js',
                ['defer']
            )->addScript(
                'dataTables.bootstrap4.js',
                '/admin/libs/datatables-bs4/js/dataTables.bootstrap4.min.js',
                ['defer']
            )->addStyle(
                'dataTables.bootstrap4.css',
                '/admin/libs/datatables-bs4/css/dataTables.bootstrap4.min.css',
                ['rel' => 'stylesheet preload', 'as' => 'style']
            );
        });

        PackageManager::create('fixedcolumns', function($package) {
            $package->addScript(
                'fixedcolumns.js',
                '/default/libs/fixedcolumns/dataTables.min.js',
                ['defer']
            )->addStyle(
                'fixedcolumns.css',
                '/default/libs/fixedcolumns/dataTables.min.css',
                ['rel' => 'stylesheet preload', 'as' => 'style']
            );
        });

        PackageManager::create('owl-carousel', function($package) {
            $package->addScript(
                'owl.carousel.js',
                '/default/libs/owl-carousel/owl.carousel.min.js',
                ['defer']
            )->addStyle(
                'owl.carousel.css',
                '/default/libs/owl-carousel/assets/owl.carousel.min.css',
                ['rel' => 'stylesheet preload', 'as' => 'style']
            )->addStyle(
                'owl.theme.css',
                '/default/libs/owl-carousel/assets/owl.theme.default.min.css',
                ['rel' => 'stylesheet preload', 'as' => 'style']
            );
        });

        PackageManager::create('slick', function($package) {
            $package->addScript(
                'slick.js',
                '/default/libs/slick/slick.min.js',
                ['defer']
            )->addStyle(
                'slick.css',
                '/default/libs/slick/slick.css',
                ['rel' => 'stylesheet preload', 'as' => 'style']
            );
        });

        PackageManager::create('smooth-scrollbar', function($package) {
            $package->addScript(
                'smooth-scrollbar.js',
                '/default/libs/smooth-scrollbar/smooth-scrollbar.js',
                ['defer']
            );
        });

        PackageManager::create('sweetalert2', function($package) {
            $package->addScript(
                'sweetalert2.js',
                '/admin/libs/sweetalert2/sweetalert2.js',
                ['defer']
            )->addStyle(
                'sweetalert2.css',
                '/admin/libs/sweetalert2-theme-bootstrap-4/bootstrap-4.css',
                ['rel' => 'stylesheet preload', 'as' => 'style']
            );
        });
    }

    // if you don't want to change anything in this method just remove it
    protected function registerMeta(): void
    {
        $this->app->singleton(MetaInterface::class, function () {
            $meta = new Meta(
                $this->app[ManagerInterface::class],
                $this->app['config']
            );

            // It just an imagination, you can automatically
            // add favicon if it exists
            if (file_exists(public_path('default/img/favicon.svg'))) {
                $meta->setFavicon(asset('default/img/favicon.svg'));
            }

            $meta->addLink('alternate', [
                'href' => LaravelLocalization::getLocalizedURL(key(config('laravellocalization.supportedLocales'))),
                'hreflang' => 'x-default'
            ]);

            $excluded_post = ExcludedPostService::execute();

            foreach(LaravelLocalization::getSupportedLocales() as $key => $item) {
                if (!$excluded_post || $excluded_post != LaravelLocalization::getLocalizedURL($key)) {
                    $meta->setHrefLang($key, LaravelLocalization::getLocalizedURL($key));
                }
            }

            // This method gets default values from config and creates tags, includes default packages, e.t.c
            // If you don't want to use default values just remove it.
            $meta->initialize();

            return $meta;
        });
    }
}
