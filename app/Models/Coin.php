<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Plank\Metable\Metable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Coin extends AppModel implements HasMedia
{
    // use HasFactory;
    use HasMediaTrait;
    use Metable;

    public $translatedAttributes = ['subtitle', 'description', 'meta_h1', 'meta_title', 'meta_description'];

    protected $fillable = ['algorithm_id', 'title', 'code', 'show_home', 'whattomine_coin_id', 'alias', 'status', 'sitemap'];

    protected $with = ['translations'];

    protected $casts = [
        'profit_per_unit' => 'array',
        'chart_data' => 'array',
        'cost_by_exchange' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function algorithm()
    {
        return $this->belongsTo(Algorithm::class);
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function ratings()
    {
        return $this->belongsToMany(Rating::class)->withPivot(['pool_data', 'hashrate']);
    }

    public function poolStats()
    {
        return $this->hasMany(PoolStats::class);
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

    protected static function boot()
    {
        parent::boot();

        static::created(function($model) {
            $model->code = mb_strtolower($model->code);
            $model->alias = $model->alias ?: AppModel::generateAlias($model->title, $model);
            $model->save();
        });

        static::updating(function($model) {
            $model->code = mb_strtolower($model->code);
            $model->alias = $model->alias ?: AppModel::generateAlias($model->title, $model);
        });
    }
}
