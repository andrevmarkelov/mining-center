<?php

namespace App\Services\Rating;

use Illuminate\Support\Facades\Facade;

class RatingService extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Rating';
    }
}
