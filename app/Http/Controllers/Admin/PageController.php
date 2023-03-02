<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\Page;

class PageController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('page_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $news = Page::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($news)
                ->filterColumn('title', function($query, $keyword) {
                    $query->whereTranslationLike('title', "%{$keyword}%", \App::getLocale());
                })
                ->editColumn('created_at', function($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                ->addColumn('action', function($data) {
                    return view('admin.components.action', [
                        'route' => 'pages',
                        'can' => 'page',
                        'id' => $data->id,
                    ]);
                })
                ->toJson();
        }

        return view('admin.pages.index');
    }

    public function create()
    {
        abort_if(Gate::denies('page_create'), 403, '403 Forbidden');

        return view('admin.pages.create');
    }

    public function store(PageRequest $request)
    {
        Page::create($request->validated());

        return response()->json([
            'success' => 'Страница успешно добавлена.',
            'redirect' => route('admin.pages.index')
        ]);
    }

    public function edit(Page $page)
    {
        abort_if(Gate::denies('page_edit'), 403, '403 Forbidden');

        return view('admin.pages.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page)
    {
        $page->update($request->validated());

        return response()->json([
            'success' => 'Страница успешно обновлена.',
            'redirect' => route('admin.pages.index')
        ]);
    }

    public function destroy(Page $page)
    {
        abort_if(Gate::denies('page_delete'), 403, '403 Forbidden');

        $page->delete();

        return back()->with('status', 'Страница успешно удалена.');
    }
}
