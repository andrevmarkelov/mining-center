<?php

namespace App\View\Composers;

use App\Models\Coin;
use App\Models\FirmwareCategory;
use App\Models\NewsCategory;

class CoinNavComposer
{
    public function compose($view)
    {
        $view->with('coins_nav', Coin::active()->whereHas('ratings')->whereIn('id', setting('menu_coins', []))->get())
            ->with('btc_halving', NewsCategory::active()->find(setting('news_add_categories')['btc_halving'] ?? ''));
        // ->with('firmwares_nav', FirmwareCategory::active()->whereHas('firmwares')->get());
    }
}
