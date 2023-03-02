<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Algorithm extends Model
{
    // use HasFactory;

    public $translatedAttributes = [];

    protected $fillable = ['title'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->title = mb_strtolower($model->title);
        });

        static::updating(function($model) {
            $model->title = mb_strtolower($model->title);
        });
    }
}
