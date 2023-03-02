<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\AppModel;
use App\Models\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;

class ServiceController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $services = Service::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($services)
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
                        'route' => 'services',
                        'can' => 'service',
                        'id' => $data->id,
                        'show' => $data->alias,
                    ]);
                })
                ->toJson();
        }

        return view('admin.services.index');
    }

    public function create()
    {
        return view('admin.services.create', [
            'cities' => City::active()->listsTranslations('title')->pluck('title', 'id')
        ]);
    }

    public function store(ServiceRequest $request)
    {
        $service = Service::create($request->validated());
        $service->cities()->sync($request->input('cities', []));

        AppModel::saveDeleteImage($service, $request, ['image']);

        return response()->json([
            'success' => 'Сервис успешно добавлен.',
            'redirect' => route('admin.services.index')
        ]);
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', [
            'service' => $service,
            'cities' => City::active()->listsTranslations('title')->pluck('title', 'id'),
        ]);
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        $service->cities()->sync($request->input('cities', []));

        AppModel::saveDeleteImage($service, $request, ['image']);

        return response()->json([
            'success' => 'Сервис успешно обновлен.',
            'redirect' => route('admin.services.index')
        ]);
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('status', 'Сервис успешно удален.');
    }
}
