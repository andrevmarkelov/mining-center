<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use App\Models\Page;
use Meta;

class PageController extends Controller
{
    public function show($alias)
    {
        $page = Page::active()->where('alias', $alias)->firstOrFail();

        Meta::setTitle($page->meta_title ?: $page->title);
        Meta::setDescription($page->meta_description);
        Meta::includePackages('select2');

        return view('pages.show', compact('page') + [
            'coins' => Coin::active()->get()
        ]);
    }
}
