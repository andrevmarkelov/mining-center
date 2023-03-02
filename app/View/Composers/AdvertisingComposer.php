<?php

namespace App\View\Composers;

use App\Models\Advertising;
use Route;

class AdvertisingComposer
{
    public function compose($view)
    {
        $view->with('pc_1', $this->getByType('pc_1'))
            ->with('pc_2', $this->getByType('pc_2'))
            ->with('mobile_1', $this->getByType('mobile_1'));
    }

    protected function getByType(string $type)
    {
        $position = preg_replace('/\..*/', '', Route::currentRouteName());

        return Advertising::active()
            ->where('type', $type)
            ->whereHasMeta('language_' . app()->getLocale())
            ->whereHasMeta('position_' . $position)
            ->first();
    }
}
