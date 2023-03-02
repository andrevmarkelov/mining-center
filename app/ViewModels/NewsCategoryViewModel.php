<?php

namespace App\ViewModels;

use App\Models\NewsCategory;
use Cache;

class NewsCategoryViewModel
{
    public static function primary()
    {
        return Cache::remember('news_category_primary', 60 * 60, function() {
            return NewsCategory::active()->whereIn('id', setting('news_categories', []))->get();
        });
    }

    public static function additional()
    {
        return NewsCategory::active()->whereIn('id', setting('news_add_categories', []))->get();
    }
}
