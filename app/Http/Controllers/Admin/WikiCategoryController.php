<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\WikiCategoryRequest;
use App\Models\AppModel;
use App\Models\WikiCategory;

class WikiCategoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wiki_category_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $wiki_categories = WikiCategory::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($wiki_categories)
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
                        'route' => 'wiki_categories',
                        'can' => 'wiki_category',
                        'id' => $data->id,
                    ]);
                })
                ->toJson();
        }

        return view('admin.wiki_categories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('wiki_category_create'), 403, '403 Forbidden');

        return view('admin.wiki_categories.create');
    }

    public function store(WikiCategoryRequest $request)
    {
        $wiki_category = WikiCategory::create($request->validated());

        AppModel::saveDeleteImage($wiki_category, $request, ['image']);

        return response()->json([
            'success' => 'Категория успешно добавлена.',
            'redirect' => route('admin.wiki_categories.index')
        ]);
    }

    public function edit(WikiCategory $wiki_category)
    {
        abort_if(Gate::denies('wiki_category_edit'), 403, '403 Forbidden');

        return view('admin.wiki_categories.edit', compact('wiki_category'));
    }

    public function update(WikiCategoryRequest $request, WikiCategory $wiki_category)
    {
        $wiki_category->update($request->validated());

        AppModel::saveDeleteImage($wiki_category, $request, ['image']);

        return response()->json([
            'success' => 'Категория успешно обновлена.',
            'redirect' => route('admin.wiki_categories.index')
        ]);
    }

    public function destroy(WikiCategory $wiki_category)
    {
        abort_if(Gate::denies('wiki_category_delete'), 403, '403 Forbidden');

        $wiki_category->delete();

        return back()->with('status', 'Категория успешно удалена.');
    }
}
