<?php

namespace App\Services\Image;

use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Image', \App\Services\Image\Image::class);
    }
}
