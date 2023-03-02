<?php

namespace App\Services\Rating;

use Illuminate\Support\ServiceProvider;

class RatingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Rating', \App\Services\Rating\Rating::class);
    }
}
