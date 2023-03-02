<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\AppModel;
use App\Models\Advertising;
use App\Http\Controllers\Controller;
use App\Services\Image\ImageService;
use App\Http\Requests\AdvertisingRequest;

class AdvertisingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('advertising_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $advertisings = Advertising::when(!request()->input('order'), function ($query) {
                $query->latest();
            });

            return datatables()->of($advertisings)
                ->editColumn('created_at', function ($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                ->editColumn('type', function ($data) {
                    return config('app_data.advertising_types')[$data->type];
                })
                ->editColumn('position', function ($data) {
                    return $data->getAllMeta()->keys()->map(function($item) {
                        return ucfirst(str_replace(['language_', 'position_'], '', $item));
                    })->implode(', ');
                })
                ->addColumn('thumb', function ($data) {
                    if ($data->getMedia('image')->count()) {
                        return ImageService::optimize($data->getFirstMediaUrl('image'), 40, 40);
                    }
                })
                ->addColumn('action', function ($data) {
                    return view('admin.components.action', [
                        'route' => 'advertisings',
                        'can' => 'advertising',
                        'id' => $data->id,
                    ]);
                })
                ->toJson();
        }

        return view('admin.advertisings.index');
    }

    public function create()
    {
        abort_if(Gate::denies('advertising_create'), 403, '403 Forbidden');

        return view('admin.advertisings.create');
    }

    public function store(AdvertisingRequest $request)
    {
        $advertising = Advertising::create($request->validated());

        AppModel::saveDeleteImage($advertising, $request, ['image']);

        $advertising->syncMeta($this->formattingMeta($request));

        return response()->json([
            'success' => 'Реклама успешно добавлена.',
            'redirect' => route('admin.advertisings.index')
        ]);
    }

    public function edit(Advertising $advertising)
    {
        abort_if(Gate::denies('advertising_edit'), 403, '403 Forbidden');

        return view('admin.advertisings.edit', compact('advertising'));
    }

    public function update(AdvertisingRequest $request, Advertising $advertising)
    {
        $advertising->update($request->validated());

        AppModel::saveDeleteImage($advertising, $request, ['image']);

        $advertising->purgeMeta();
        $advertising->syncMeta($this->formattingMeta($request));

        return response()->json([
            'success' => 'Реклама успешно обновлена.',
            'redirect' => route('admin.advertisings.index')
        ]);
    }

    public function destroy(Advertising $advertising)
    {
        abort_if(Gate::denies('advertising_delete'), 403, '403 Forbidden');

        $advertising->delete();

        return back()->with('status', 'Реклама успешно удалена.');
    }

    protected function formattingMeta($request) {
        $meta_data = [];

        foreach ($request->input('language') as $item) {
            $meta_data['language_' . $item] = 1;
        }
        foreach ($request->input('position') as $item) {
            $meta_data['position_' . $item] = 1;
        }

        return $meta_data;
    }
}
