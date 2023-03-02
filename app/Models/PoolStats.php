<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoolStats extends Model
{
    // use HasFactory;

    public $timestamps = false;

    public $fillable = ['coin_id', 'miner', 'block_height', 'difficulty', 'time'];

    public $dates = ['time'];

    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }
}
