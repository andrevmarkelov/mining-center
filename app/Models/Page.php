<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends AppModel
{
    // use HasFactory;

    public $translatedAttributes = ['title', 'subtitle', 'description', 'meta_title', 'meta_description'];

    protected $fillable = ['type', 'alias', 'status'];

    protected $with = ['translations'];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
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
