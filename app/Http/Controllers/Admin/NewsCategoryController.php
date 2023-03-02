<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\AppModel;
use App\Models\NewsCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsCategoryRequest;

class NewsCategoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('news_category_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $news_categories = NewsCategory::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($news_categories)
                ->filterColumn('title', function($query, $keyword) {
                    $query->whereTranslationLike('title', "%{$keyword}%", \App::getLocale());
                })
                ->editColumn('created_at', function($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                // ->addColumn('thumb', function($data) {
                //     return $data->thumb;
                // })
                ->addColumn('action', function($data) {
                    return view('admin.components.action', [
                        'route' => 'news_categories',
                        'can' => 'news_category',
                        'id' => $data->id,
                    ]);
                })
                ->toJson();
        }

        return view('admin.news_categories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('news_category_create'), 403, '403 Forbidden');

        return view('admin.news_categories.create');
    }

    public function store(NewsCategoryRequest $request)
    {
        $news_category = NewsCategory::create($request->validated());

        AppModel::saveDeleteImage($news_category, $request, ['image']);

        return response()->json([
            'success' => 'Категория успешно добавлена.',
            'redirect' => route('admin.news_categories.index')
        ]);
    }

    public function edit(NewsCategory $news_category)
    {
        abort_if(Gate::denies('news_category_edit'), 403, '403 Forbidden');

        return view('admin.news_categories.edit', compact('news_category'));
    }

    public function update(NewsCategoryRequest $request, NewsCategory $news_category)
    {
        $news_category->update($request->validated());

        AppModel::saveDeleteImage($news_category, $request, ['image']);

        return response()->json([
            'success' => 'Категория успешно обновлена.',
            'redirect' => route('admin.news_categories.index')
        ]);
    }

    public function destroy(NewsCategory $news_category)
    {
        abort_if(Gate::denies('news_category_delete'), 403, '403 Forbidden');

        $news_category->delete();

        return back()->with('status', 'Категория успешно удалена.');
    }
}
