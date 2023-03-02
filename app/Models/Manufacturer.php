<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    // use HasFactory;

    public $translatedAttributes = [];

    protected $fillable = ['title', 'status'];

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }
}
