<?php

namespace App\Http\Controllers;

use App\Models\Wiki;
use App\Models\WikiCategory;
use Meta;

class WikiController extends Controller
{
    public function index($category = null)
    {
        if ($category) {
            $category = WikiCategory::active()->where('alias', $category)->firstOrFail();

            Meta::setTitle($category->meta_title ?: $category->title);
            Meta::setDescription($category->meta_description);
        }

        $wiki_list = Wiki::with('media')->active()
            ->select('wiki.*')
            ->leftJoin('wiki_translations', 'wiki.id', 'wiki_translations.wiki_id')
            ->where('locale', app()->getLocale())
            ->where('title', '<>', '')
            ->when($category, function ($query) use ($category) {
                $query->where('wiki_category_id', $category->id);
            })
            ->when($search = request()->input('search'), function($query) use ($search) {
                $query->where('title', 'like', "%" . $search . "%")->orWhere('description', 'like', "%" . $search . "%");
            })
            ->orderByDesc('wiki.id')
            ->paginate(6)->onEachSide(1);

        $wiki_list->setPath(url()->current());

        $categories = WikiCategory::with('media')->active()->has('wikiList')->get();

        Meta::setCanonical(route('wiki'));

        return view('wiki.index', compact('wiki_list', 'category', 'categories'));
    }

    public function show($alias)
    {
        $wiki = Wiki::active()->where('alias', $alias)->firstOrFail();

        static::openGraph($wiki);
        Meta::setTitle($wiki->meta_title ?: $wiki->title);
        Meta::setDescription($wiki->meta_description);
        Meta::includePackages('owl-carousel');

        $related = Wiki::with('media')->active()
            ->select('wiki.*')
            ->leftJoin('wiki_translations', 'wiki.id', 'wiki_translations.wiki_id')
            ->where('locale', app()->getLocale())
            ->where('wiki.id', '<>', $wiki->id)
            ->where('title', '<>', '')
            ->limit(8)->get();

        return view('wiki.show', compact('wiki', 'related'));
    }
}
