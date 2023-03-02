<?php

namespace App\Observers;

use App\Jobs\ClearNewsCacheJob;

class NewsObserver
{
    public function updated()
    {
        dispatch(new ClearNewsCacheJob);
    }

    public function deleted()
    {
        dispatch(new ClearNewsCacheJob);
    }
}
