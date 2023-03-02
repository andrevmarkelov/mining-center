<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Cache;
use LaravelLocalization;

class ClearNewsCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        foreach (LaravelLocalization::getSupportedLocales() as $key => $item) {
            Cache::forget('news_latest_' . $key);
            Cache::forget('news_reviews_' . $key);
            Cache::forget('news_people_' . $key);
            Cache::forget('news_events_' . $key);
            Cache::forget('news_investment_' . $key);
        }
    }
}
