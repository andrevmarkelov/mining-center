<?php

namespace App\Services;

use LaravelLocalization;

class ExcludedPostService
{
    public static function execute()
    {
        // Записи в которых может не быть перевода на другой язык
        if (\Route::is(['wiki.show', 'news.show'])) {
            $model = 'App\Models\\' . str_replace('Controller', '', class_basename(\Route::current()->controller));

            if ($model = $model::where('alias', \Route::input('alias'))->first()) {
                foreach (LaravelLocalization::getSupportedLocales() as $key => $item) {
                    if (empty($model->translate($key)->title)) {
                        return LaravelLocalization::getLocalizedURL($key);
                    }
                }
            }
        }

        return false;
    }
}
