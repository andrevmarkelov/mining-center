<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use App\Http\Requests\ManufacturerRequest;

class ManufacturerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('manufacturer_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $manufacturers = Manufacturer::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($manufacturers)
                ->editColumn('created_at', function($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                ->addColumn('action', function($data) {
                    return view('admin.components.action', [
                        'route' => 'manufacturers',
                        'can' => 'manufacturer',
                        'id' => $data->id,
                    ]);
                })
                ->toJson();
        }

        return view('admin.manufacturers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('manufacturer_create'), 403, '403 Forbidden');

        return view('admin.manufacturers.create');
    }

    public function store(ManufacturerRequest $request)
    {
        Manufacturer::create($request->validated());

        return response()->json([
            'success' => 'Производитель успешно добавлен.',
            'redirect' => route('admin.manufacturers.index')
        ]);
    }

    public function edit(Manufacturer $manufacturer)
    {
        abort_if(Gate::denies('manufacturer_edit'), 403, '403 Forbidden');

        return view('admin.manufacturers.edit', compact('manufacturer'));
    }

    public function update(ManufacturerRequest $request, Manufacturer $manufacturer)
    {
        $manufacturer->update($request->validated());

        return response()->json([
            'success' => 'Производитель успешно обновлен.',
            'redirect' => route('admin.manufacturers.index')
        ]);
    }

    public function destroy(Manufacturer $manufacturer)
    {
        abort_if(Gate::denies('manufacturer_delete'), 403, '403 Forbidden');

        $manufacturer->delete();

        return back()->with('status', 'Производитель успешно удален.');
    }
}
