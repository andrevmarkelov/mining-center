<?php

namespace App\Models;

use App\Services\Image\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Equipment extends AppModel implements HasMedia
{
    // use HasFactory;
    use HasMediaTrait;

    protected $table = 'equipments';

    public $translatedAttributes = ['title', 'add_title', 'description', 'add_description', 'meta_title', 'meta_description'];

    protected $fillable = ['price', 'available', 'alias', 'status', 'sitemap', 'coin_id', 'firmware_id', 'manufacturer_id', 'hashrate', 'power'];

    protected $with = ['translations'];

    protected $casts = [
        'profit_data' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }

    public function firmware()
    {
        return $this->belongsTo(Firmware::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('gallery')->onlyKeepLatest(12);
    }

    public function getThumbAttribute()
    {
        $image = config('image.no_image');

        if ($this->getMedia('image')->count()) {
            $image = $this->getFirstMediaUrl('image');
        }

        return ImageService::equipmentThumb($image);
    }

    public function getImageAttribute()
    {
        if ($this->getMedia('image')->count()) {
            return $this->getFirstMediaUrl('image');
        }
    }

    public function getGalleryAttribute()
    {
        if ($this->getMedia('gallery')->count()) {
            return $this->getMedia('gallery');
        }
    }

    public function setFirmwareIdAttribute($data)
    {
        $this->attributes['firmware_id'] = $data ? $data : null;
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
