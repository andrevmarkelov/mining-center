<?php

namespace App\Http\Controllers\Api;

use App\Models\NewsCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsCategoriesResource;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::active()->when($keyword = request()->input('q'), function($query) use ($keyword) {
            $query->whereTranslationLike('title', "%{$keyword}%", app()->getLocale());
        })->latest()->get();

        return NewsCategoriesResource::collection($categories);
    }
}
