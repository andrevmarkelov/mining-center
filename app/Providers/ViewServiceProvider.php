<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composers([
            'App\View\Composers\CoinNavComposer' => ['inc.header'],
            'App\View\Composers\PageComposer'  => ['components.breadcrumb', 'components.page_description'],
            'App\View\Composers\AdvertisingComposer' => ['inc.promotion_x', 'inc.promotion_y'],
        ]);
    }
}
