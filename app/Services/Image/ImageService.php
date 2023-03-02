<?php

namespace App\Services\Image;

use Illuminate\Support\Facades\Facade;

class ImageService extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Image';
    }
}
