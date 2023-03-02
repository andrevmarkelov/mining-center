<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\MiningRequest;
use App\Models\AppModel;
use App\Models\Mining;

class MiningController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('mining_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $mining = Mining::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($mining)
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
                        'route' => 'mining',
                        'can' => 'mining',
                        'id' => $data->id,
                    ]);
                })
                ->toJson();
        }

        return view('admin.mining.index');
    }

    public function create()
    {
        abort_if(Gate::denies('mining_create'), 403, '403 Forbidden');

        return view('admin.mining.create');
    }

    public function store(MiningRequest $request)
    {
        $mining = Mining::create($request->validated());

        AppModel::saveDeleteImage($mining, $request, ['image']);

        return response()->json([
            'success' => 'Сервис успешно добавлен.',
            'redirect' => route('admin.mining.index')
        ]);
    }

    public function edit(Mining $mining)
    {
        abort_if(Gate::denies('mining_edit'), 403, '403 Forbidden');

        return view('admin.mining.edit', compact('mining'));
    }

    public function update(MiningRequest $request, Mining $mining)
    {
        $mining->update($request->validated());

        AppModel::saveDeleteImage($mining, $request, ['image']);

        return response()->json([
            'success' => 'Сервис успешно обновлен.',
            'redirect' => route('admin.mining.index')
        ]);
    }

    public function destroy(Mining $mining)
    {
        abort_if(Gate::denies('mining_delete'), 403, '403 Forbidden');

        $mining->delete();

        return back()->with('status', 'Сервис успешно удален.');
    }
}
