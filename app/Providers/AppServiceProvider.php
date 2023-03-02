<?php

namespace App\Providers;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsCategoryTranslation;
use App\Models\NewsTranslation;
use App\Observers\NewsCategoryObserver;
use App\Observers\NewsObserver;
use Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
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
        Schema::defaultStringLength(191);

        Paginator::useBootstrap();

        Blade::directive('routeActive', function ($route) {
            return "<?php echo Route::is($route) ? 'active' : ''; ?>";
        });

        JsonResource::withoutWrapping();

        News::observe(NewsObserver::class);
        NewsTranslation::observe(NewsObserver::class);
        NewsCategory::observe(NewsCategoryObserver::class);
        NewsCategoryTranslation::observe(NewsCategoryObserver::class);
    }
}
