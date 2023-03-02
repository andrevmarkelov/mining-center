<?php

namespace App\Observers;

use App\Jobs\ClearNewsCategoryCacheJob;

class NewsCategoryObserver
{
    public function updated()
    {
        dispatch(new ClearNewsCategoryCacheJob);
    }

    public function deleted()
    {
        dispatch(new ClearNewsCategoryCacheJob);
    }
}
