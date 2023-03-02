<?php

namespace App\Models;

use App\Services\Image\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Wiki extends AppModel implements HasMedia
{
    // use HasFactory;
    use HasMediaTrait;

    protected $table = 'wiki';

    public $translatedAttributes = ['title', 'description', 'meta_title', 'meta_description'];

    protected $fillable = ['wiki_category_id', 'alias', 'status', 'sitemap'];

    protected $with = ['translations'];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(WikiCategory::class, 'wiki_category_id');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function getThumbAttribute()
    {
        $image = config('image.no_image');

        if ($this->getMedia('image')->count()) {
            $image = $this->getFirstMediaUrl('image');
        }

        return ImageService::wikiThumb($image);
    }

    public function getImageAttribute()
    {
        if ($this->getMedia('image')->count()) {
            return $this->getFirstMediaUrl('image');
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function($model) {
            $model->alias = $model->alias ?: AppModel::generateAlias($model->translate('ru')->title, $model);
            $model->save();
        });

        static::updating(function($model) {
            $model->alias = $model->alias ?: AppModel::generateAlias($model->translate('ru')->title, $model);
        });
    }
}
