<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::active()->when($keyword = request()->input('q'), function($query) use ($keyword) {
            $query->whereTranslationLike('title', "%{$keyword}%", app()->getLocale());
        })->latest()->get();

        return NewsResource::collection($news);
    }
}
