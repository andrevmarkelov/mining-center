<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Plank\Metable\Metable;

class Country extends AppModel
{
    // use HasFactory;
    use Metable;

    public $translatedAttributes = ['title'];

    protected $fillable = ['alias', 'latitude', 'longitude', 'status'];

    protected $with = ['translations'];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    // public function dataCenters()
    // {
    //     return $this->belongsToMany(DataCenter::class);
    // }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->alias = $model->alias ?: AppModel::generateAlias($model->translate('ru')->title, $model);
            $model->save();
        });

        static::updating(function ($model) {
            $model->alias = $model->alias ?: AppModel::generateAlias($model->translate('ru')->title, $model);
        });
    }
}
