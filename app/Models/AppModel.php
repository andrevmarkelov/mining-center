<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class AppModel extends Model implements TranslatableContract
{
    use Translatable;

    public function themeDate(Carbon $date)
    {
        if (app()->getLocale() == 'en') {
            return gmdate('M. d, Y \a\t H:i e', strtotime($date));
        } else {
            return $date->isoFormat('D MMMM Y · ') . $date->format('H:i');
        }
    }

    public static function generateAlias($string, $model)
    {
        $string = strtr(mb_strtolower($string), [
            'х' => 'h',
            'ж' => 'zh',
            'й' => 'j',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'shch',
        ]);

        $alias = Str::slug($string);

        if (empty($alias)) {
            $alias = $model->id;
        } elseif ($model->where([['alias', '=', $alias], ['id', '<>', $model->id]])->exists()) {
            $alias = $model->id . '-' . $alias;
        }

        return $alias;
    }

    public static function saveDeleteImage($model, $request, array $names)
    {
        foreach ($names as $name) {
            if ($image = $request->file($name)) {
                $model->addMedia($image)->usingFileName(uniqid() . '.' . $image->extension())->toMediaCollection($name);
            }

            if ($request->input('delete_' . $name)) {
                $model->getFirstMedia($name)->delete();
            }
        }
    }

    public static function saveGallery($model, $request, $name)
    {
        if ($gallery = $request->file($name)) {
            foreach ($gallery as $item) {
                $model->addMedia($item)->usingFileName(uniqid() . '.' . $item->extension())->toMediaCollection($name);
            }
        }
    }
}
