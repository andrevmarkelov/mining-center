<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\WikiRequest;
use App\Models\AppModel;
use App\Models\Wiki;
use App\Models\WikiCategory;

class WikiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wiki_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $wiki = Wiki::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($wiki)
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
                        'route' => 'wiki',
                        'can' => 'wiki',
                        'id' => $data->id,
                        'show' => $data->alias,
                    ]);
                })
                ->toJson();
        }

        return view('admin.wiki.index');
    }

    public function create()
    {
        abort_if(Gate::denies('wiki_create'), 403, '403 Forbidden');

        return view('admin.wiki.create', [
            'categories' => WikiCategory::active()->listsTranslations('title')->pluck('title', 'id')
        ]);
    }

    public function store(WikiRequest $request)
    {
        $wiki = Wiki::create($request->validated());
        $wiki->user_id = auth()->id();
        $wiki->update();

        AppModel::saveDeleteImage($wiki, $request, ['image']);

        return response()->json([
            'success' => 'Wiki успешно добавлен.',
            'redirect' => route('admin.wiki.index')
        ]);
    }

    public function edit(Wiki $wiki)
    {
        abort_if(Gate::denies('wiki_edit'), 403, '403 Forbidden');

        $categories = WikiCategory::active()->listsTranslations('title')->pluck('title', 'id');

        return view('admin.wiki.edit', compact('wiki', 'categories'));
    }

    public function update(WikiRequest $request, Wiki $wiki)
    {
        $wiki->update($request->validated());

        AppModel::saveDeleteImage($wiki, $request, ['image']);

        return response()->json([
            'success' => 'Wiki успешно обновлен.',
            'redirect' => route('admin.wiki.index')
        ]);
    }

    public function destroy(Wiki $wiki)
    {
        abort_if(Gate::denies('wiki_delete'), 403, '403 Forbidden');

        $wiki->delete();

        return back()->with('status', 'Wiki успешно удален.');
    }
}
