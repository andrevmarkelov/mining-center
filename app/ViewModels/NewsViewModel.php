<?php

namespace App\ViewModels;

use Cache;
use App\Models\News;
use App\Models\NewsCategory;

class NewsViewModel
{
    public static function id($type)
    {
        return setting('news_categories')[$type] ?? null;
    }

    public static function idAdd($type)
    {
        return setting('news_add_categories')[$type] ?? null;
    }

    public static function baseQuery()
    {
        return News::with('media')->active()
            ->select('news.*')
            ->leftJoin('news_translations', 'news.id', 'news_translations.news_id')
            ->where('locale', app()->getLocale())
            ->where('title', '<>', '')
            ->orderByDesc('sort_order')
            ->orderByDesc('news.created_at');
    }

    public static function latest()
    {
        return Cache::remember('news_latest_' . app()->getLocale(), 60 * 60, function () {
            return self::baseQuery()->where('news.id', '<>', setting('news_hot_topic'))->limit(8)->get();
        });
    }

    public static function reviews()
    {
        return Cache::remember('news_reviews_' . app()->getLocale(), 60 * 60, function () {
            return self::baseQuery()
                ->whereHas('categories', function ($query) {
                    $query->where('news_category_id', self::id('reviews'));
                })->limit(12)->get();
        });
    }

    public static function people()
    {
        return Cache::remember('news_people_' . app()->getLocale(), 60 * 60, function () {
            return self::baseQuery()
                ->whereHas('categories', function ($query) {
                    $query->where('news_category_id', self::id('people'));
                })->limit(9)->get();
        });
    }

    public static function events()
    {
        return Cache::remember('news_events_' . app()->getLocale(), 60 * 60, function () {
            return self::baseQuery()
                ->whereHas('categories', function ($query) {
                    $query->where('news_category_id', self::id('events'));
                })->limit(4)->get();
        });
    }

    public static function investment()
    {
        return Cache::remember('news_investment_' . app()->getLocale(), 60 * 60, function () {
            return self::baseQuery()
                ->whereHas('categories', function ($query) {
                    $query->where('news_category_id', self::idAdd('investment'));
                })->limit(12)->get();
        });
    }

    public static function category(NewsCategory $category)
    {
        return self::baseQuery()
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('news_category_id', $category->id);
            })->paginate(14);
    }

    public static function popular(NewsCategory $category)
    {
        return self::baseQuery()
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('news_category_id', $category->id);
            })->orderByDesc('view')->limit(3)->get();
    }

    public static function related(News $news)
    {
        return self::baseQuery()
            ->whereHas('categories', function ($query) use ($news) {
                $query->whereIn('news_category_id', $news->categories->pluck('id'));
            })
            ->where('news.id', '<>', $news->id)
            ->inRandomOrder()
            ->limit(8)->get();
    }

    public static function popularInDetails(News $news)
    {
        return self::baseQuery()
            ->whereHas('categories', function ($query) use ($news) {
                $query->whereIn('news_category_id', $news->categories->pluck('id'));
            })
            ->where('news.id', '<>', $news->id)
            ->orderByDesc('view')
            ->limit(7)->get();
    }
}
