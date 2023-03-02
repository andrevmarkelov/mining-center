<?php

namespace App\Http\Controllers\Api;

use App\Models\Coin;
use App\Http\Resources\CoinResource;
use App\Http\Controllers\Controller;

class CoinController extends Controller
{
    public function index()
    {
        $coins = Coin::active()->when($keyword = request()->input('q'), function($query) use ($keyword) {
            $query->where('title', 'LIKE', "%{$keyword}%");
        })->latest()->get();

        return CoinResource::collection($coins);
    }
}
