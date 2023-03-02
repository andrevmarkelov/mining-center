<?php

namespace App\ViewModels;

use App\Models\Coin;
use DB;

class RatingViewModel
{
    public static function poolStats(Coin $coin)
    {
        $period = request()->input('period', 24);

        return $coin->poolStats()
            ->select('coin_id', 'miner', DB::raw('COUNT(block_height) as block_count, AVG(difficulty) as difficulty, MIN(time) as min_time, MAX(time) as max_time'))
            ->with('coin.algorithm')
            ->where('time', '>=', now()->subHour($period)->setTimezone('UTC'))
            ->having('block_count', '>', 1)
            ->latest('block_count')
            ->groupBy('miner')
            ->get()
            ->map(function ($item) {
                $time_interval = now()->parse($item->max_time)->diffInSeconds(now()->parse($item->min_time)) / $item->block_count;

                switch ($item->coin->algorithm->title) {
                    // sha-256
                    default:
                        $coefficient = pow(2, 32);
                }

                $item->hashrate = ($item->difficulty * $coefficient) / $time_interval;

                return $item;
            });
    }
}
