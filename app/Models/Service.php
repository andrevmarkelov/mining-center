<?php

namespace App\Models;

use App\Services\Image\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Plank\Metable\Metable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Service extends AppModel implements HasMedia
{
    // use HasFactory;
    use HasMediaTrait;
    use Metable;

    public $translatedAttributes = ['title', 'description', 'meta_title', 'meta_description'];

    protected $fillable = ['equipment_type', 'alias', 'contacts', 'sort_order', 'status', 'sitemap'];

    protected $with = ['translations'];

    protected $casts = [
        'contacts' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function cities()
    {
        return $this->belongsToMany(City::class);
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

        return ImageService::baseThumb($image);
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
