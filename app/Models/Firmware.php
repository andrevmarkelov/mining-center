<?php

namespace App\Models;

use App\Services\Image\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Firmware extends AppModel implements HasMedia
{
    // use HasFactory;
    use HasMediaTrait;

    protected $table = 'firmwares';

    public $translatedAttributes = ['title', 'description', 'add_description', 'meta_title', 'meta_description'];

    protected $fillable = ['firmware_category_id', 'alias', 'status', 'sitemap', 'sort_order'];

    protected $with = ['translations'];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function category()
    {
        return $this->belongsTo(FirmwareCategory::class, 'firmware_category_id', 'id');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('attach');
    }

    public function getThumbAttribute()
    {
        $image = config('image.no_image');

        if ($this->getMedia('image')->count()) {
            $image = $this->getFirstMediaUrl('image');
        }

        return ImageService::firmwareThumb($image);
    }

    public function getImageAttribute()
    {
        if ($this->getMedia('image')->count()) {
            return $this->getFirstMediaUrl('image');
        }
    }

    public function getAttachAttribute()
    {
        if ($this->getMedia('attach')->count()) {
            return $this->getMedia('attach');
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
