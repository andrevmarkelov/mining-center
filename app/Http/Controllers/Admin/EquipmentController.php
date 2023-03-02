<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Coin;
use App\Models\AppModel;
use App\Models\Firmware;
use App\Models\Equipment;
use App\Models\Manufacturer;
use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentRequest;

class EquipmentController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('equipment_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $equipments = Equipment::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($equipments)
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
                        'route' => 'equipments',
                        'can' => 'equipment',
                        'id' => $data->id,
                        'show' => $data->alias,
                    ]);
                })
                ->toJson();
        }

        return view('admin.equipments.index');
    }

    public function create()
    {
        abort_if(Gate::denies('equipment_create'), 403, '403 Forbidden');

        return view('admin.equipments.create', [
            'coins' => Coin::active()->get(),
            'firmwares' => Firmware::active()->listsTranslations('title')->pluck('title', 'id'),
            'manufacturers' => Manufacturer::active()->pluck('title', 'id'),
        ]);
    }

    public function store(EquipmentRequest $request)
    {
        $equipment = Equipment::create($request->validated());

        AppModel::saveDeleteImage($equipment, $request, ['image']);
        AppModel::saveGallery($equipment, $request, 'gallery');

        return response()->json([
            'success' => 'Оборудование успешно добавлено.',
            'redirect' => route('admin.equipments.index')
        ]);
    }

    public function edit(Equipment $equipment)
    {
        abort_if(Gate::denies('equipment_edit'), 403, '403 Forbidden');

        return view('admin.equipments.edit', [
            'equipment' => $equipment,
            'coins' => Coin::active()->get(),
            'firmwares' => Firmware::active()->listsTranslations('title')->pluck('title', 'id'),
            'manufacturers' => Manufacturer::active()->pluck('title', 'id'),
        ]);
    }

    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        $equipment->parse_time = null;
        $equipment->update($request->validated());

        AppModel::saveDeleteImage($equipment, $request, ['image']);
        AppModel::saveGallery($equipment, $request, 'gallery');

        return response()->json([
            'success' => 'Оборудование успешно обновлено.',
            'redirect' => route('admin.equipments.index')
        ]);
    }

    public function destroy(Equipment $equipment)
    {
        abort_if(Gate::denies('equipment_delete'), 403, '403 Forbidden');

        $equipment->delete();

        return back()->with('status', 'Оборудование успешно удалено.');
    }
}
