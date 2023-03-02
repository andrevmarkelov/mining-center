<?php

namespace App\Actions;

use App\Models\News;

class UpdateNewsViewed
{
    public function handle(News $news)
    {
        $cur_cookie = 'news_' . $news->id;

        if (!isset($_COOKIE[$cur_cookie])) {
            setcookie($cur_cookie, true,  time() + 24 * 3600);

            $news->view = $news->view + 1;
            $news->update();
        }
    }
}
