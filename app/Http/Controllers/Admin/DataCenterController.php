<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\City;
use App\Models\Country;
use App\Models\AppModel;
use App\Models\DataCenter;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataCenterRequest;

class DataCenterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('data_center_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $data_centers = DataCenter::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($data_centers)
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
                        'route' => 'data_centers',
                        'can' => 'data_center',
                        'id' => $data->id,
                        'show' => $data->alias,
                    ]);
                })
                ->toJson();
        }

        return view('admin.data_centers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('data_center_create'), 403, '403 Forbidden');

        return view('admin.data_centers.create', [
            // 'countries' => Country::active()->listsTranslations('title')->pluck('title', 'id')
            'cities' => City::active()->listsTranslations('title')->pluck('title', 'id')
        ]);
    }

    public function store(DataCenterRequest $request)
    {
        $data_center = DataCenter::create($request->validated());
        // $data_center->countries()->sync($request->input('countries', []));
        $data_center->cities()->sync($request->input('cities', []));
        $data_center->setMeta('contacts', $request->input('contacts'));
        $data_center->setMeta('show_contacts', $request->input('show_contacts'));
        $data_center->setMeta('is_partner', $request->input('is_partner'));

        AppModel::saveDeleteImage($data_center, $request, ['image']);

        return response()->json([
            'success' => 'Дата центр успешно добавлен.',
            'redirect' => route('admin.data_centers.index')
        ]);
    }

    public function edit(DataCenter $data_center)
    {
        abort_if(Gate::denies('data_center_edit'), 403, '403 Forbidden');

        return view('admin.data_centers.edit', [
            'data_center' => $data_center,
            // 'countries' => Country::active()->listsTranslations('title')->pluck('title', 'id'),
            'cities' => City::active()->listsTranslations('title')->pluck('title', 'id'),
        ]);
    }

    public function update(DataCenterRequest $request, DataCenter $data_center)
    {
        $data_center->update($request->validated());
        // $data_center->countries()->sync($request->input('countries', []));
        $data_center->cities()->sync($request->input('cities', []));
        $data_center->setMeta('contacts', $request->input('contacts'));
        $data_center->setMeta('show_contacts', $request->input('show_contacts'));
        $data_center->setMeta('is_partner', $request->input('is_partner'));

        AppModel::saveDeleteImage($data_center, $request, ['image']);

        return response()->json([
            'success' => 'Дата центр успешно обновлен.',
            'redirect' => route('admin.data_centers.index')
        ]);
    }

    public function destroy(DataCenter $data_center)
    {
        abort_if(Gate::denies('data_center_delete'), 403, '403 Forbidden');

        $data_center->delete();

        return back()->with('status', 'Дата центр успешно удален.');
    }
}
