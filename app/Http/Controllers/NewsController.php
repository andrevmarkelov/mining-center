<?php

namespace App\Http\Controllers;

use Meta;
use App\Models\News;
use App\Models\NewsCategory;
use App\Actions\UpdateNewsViewed;
use App\ViewModels\NewsViewModel;
use App\ViewModels\NewsCategoryViewModel;

class NewsController extends Controller
{
    public function index()
    {
        Meta::includePackages('owl-carousel');

        return view('news.index', [
            'news' => NewsViewModel::latest(),
            'news_reviews' => NewsViewModel::reviews(),
            'news_people' => NewsViewModel::people(),
            'news_investment' => NewsViewModel::investment(),
            'news_hot_topic' => News::active()->find(setting('news_hot_topic')),
            'news_categories' => NewsCategoryViewModel::primary(),
        ]);
    }

    public function category($alias)
    {
        $category = NewsCategory::active()->where('alias', $alias)->firstOrFail();
        $news = NewsViewModel::category($category);

        Meta::setTitle($category->meta_title ?: $category->title);
        Meta::setDescription($category->meta_description);

        if (request()->ajax() && request()->has('page')) {
            return [
                'news' => view('news._list_category', compact('news'))->render(),
                'current_page' => $news->path() . '?page=' . $news->currentPage(),
                'next_page' => $news->nextPageUrl()
            ];
        }

        return view('news.category', compact('category', 'news') + [
            'popular' => NewsViewModel::popular($category)
        ]);
    }

    public function show($alias, UpdateNewsViewed $action)
    {
        $news = News::active()->where('alias', $alias)->firstOrFail();

        static::openGraph($news);
        Meta::setTitle($news->meta_title ?: $news->title);
        Meta::setDescription($news->meta_description);
        Meta::includePackages('slick', 'owl-carousel');

        $action->handle($news);

        return view('news.show', compact('news') + [
            'related' => NewsViewModel::related($news),
            'popular' => NewsViewModel::popularInDetails($news),
        ]);
    }
}
