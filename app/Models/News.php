<?php

namespace App\Models;

use App\Services\Image\ImageService;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use VanOns\Laraberg\Traits\RendersContent;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends AppModel implements HasMedia
{
    // use HasFactory;
    use HasMediaTrait;
    use RendersContent;

    public $translatedAttributes = ['title', 'description', 'meta_title', 'meta_description'];

    protected $fillable = ['alias', 'status', 'sitemap', 'publish_from', 'sort_order'];

    protected $with = ['translations'];

    public function scopeActive($query)
    {
        return $query->where('status', '1')
            ->where(function ($q) {
                $q->whereNotNull('publish_from')->where('publish_from', '<=', now()->toDateTimeString());
            })->orWhereNull('publish_from');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(NewsCategory::class);
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

        return ImageService::newsThumb($image);
    }

    public function getImageAttribute()
    {
        if ($this->getMedia('image')->count()) {
            return $this->getFirstMediaUrl('image');
        }
    }

    public function setPublishFromAttribute($value)
    {
        $this->attributes['publish_from'] = $value ?: null;

        if ($value) {
            $this->attributes['created_at'] = $value;
        } else if ($this->getOriginal('publish_from') && !$value) {
            $this->attributes['created_at'] = now();
        }
    }

    public function getPublishFromAttribute($value)
    {
        return $value ? date('Y-m-d\TH:i', strtotime($value)) : null;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->alias = $model->alias ?: AppModel::generateAlias($model->translate('ru')->title ?? '', $model);
            $model->save();
        });

        static::updating(function ($model) {
            $model->alias = $model->alias ?: AppModel::generateAlias($model->translate('ru')->title ?? '', $model);
        });
    }
}
