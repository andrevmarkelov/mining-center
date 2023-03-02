<?php

namespace App\Models;

use App\Services\Image\RatingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Rating extends AppModel implements HasMedia
{
    // use HasFactory;
    use HasMediaTrait;

    public $translatedAttributes = [];

    protected $fillable = ['title', 'link', 'ref_link', 'review_link', 'status'];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function coins()
    {
        return $this->belongsToMany(Coin::class);
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
