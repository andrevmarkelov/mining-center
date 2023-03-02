<?php

namespace App\Models;

use Plank\Metable\Metable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertising extends AppModel implements HasMedia
{
    // use HasFactory;
    use HasMediaTrait;
    use Metable;

    public $translatedAttributes = [];

    protected $fillable = ['type', 'link', 'nofollow', 'status'];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function getImageAttribute()
    {
        if ($this->getMedia('image')->count()) {
            return $this->getFirstMediaUrl('image');
        }
    }
}
