<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Models\AppModel;
use App\Models\News;
use App\Models\NewsCategory;

class NewsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('news_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $news = News::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($news)
                ->filterColumn('title', function($query, $keyword) {
                    $query->whereTranslationLike('title', "%{$keyword}%", \App::getLocale());
                })
                ->editColumn('created_at', function($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                ->addColumn('thumb', function($data) {
                    return $data->thumb;
                })
                ->addColumn('action', function($data) {
                    return view('admin.components.action', [
                        'route' => 'news',
                        'can' => 'news',
                        'id' => $data->id,
                        'show' => $data->alias,
                    ]);
                })
                ->toJson();
        }

        return view('admin.news.index');
    }

    public function create()
    {
        abort_if(Gate::denies('news_create'), 403, '403 Forbidden');

        return view('admin.news.create', [
            'categories' => NewsCategory::active()->listsTranslations('title')->pluck('title', 'id')
        ]);
    }

    public function store(NewsRequest $request)
    {
        $news = News::create($request->validated());
        $news->user_id = auth()->id();
        $news->update();

        $news->categories()->sync($request->input('categories', []));

        AppModel::saveDeleteImage($news, $request, ['image']);

        return response()->json([
            'success' => 'Новость успешно добавлена.',
            'redirect' => route('admin.news.edit', ['news' => $news->id, 'lang' => $request->input('lang')])
        ]);
    }

    public function edit(News $news)
    {
        abort_if(Gate::denies('news_edit'), 403, '403 Forbidden');

        $categories = NewsCategory::active()->listsTranslations('title')->pluck('title', 'id');

        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(NewsRequest $request, News $news)
    {
        $news->update($request->validated());
        $news->categories()->sync($request->input('categories', []));

        AppModel::saveDeleteImage($news, $request, ['image']);

        return response()->json([
            'success' => 'Новость успешно обновлена.',
            'redirect' => route('admin.news.edit', ['news' => $news->id, 'lang' => $request->input('lang')])
        ]);
    }

    public function destroy(News $news)
    {
        abort_if(Gate::denies('news_delete'), 403, '403 Forbidden');

        $news->delete();

        return back()->with('status', 'Новость успешно удалена.');
    }
}
