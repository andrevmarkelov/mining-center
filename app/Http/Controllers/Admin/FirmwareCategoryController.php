<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\FirmwareCategoryRequest;
use App\Models\AppModel;
use App\Models\FirmwareCategory;

class FirmwareCategoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('firmware_category_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $firmware_categories = FirmwareCategory::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($firmware_categories)
                ->editColumn('created_at', function($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                ->addColumn('thumb', function($data) {
                    return $data->thumb;
                })
                ->addColumn('action', function($data) {
                    return view('admin.components.action', [
                        'route' => 'firmware_categories',
                        'can' => 'firmware_category',
                        'id' => $data->id,
                    ]);
                })
                ->toJson();
        }

        return view('admin.firmware_categories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('firmware_category_create'), 403, '403 Forbidden');

        return view('admin.firmware_categories.create');
    }

    public function store(FirmwareCategoryRequest $request)
    {
        $firmware_category = FirmwareCategory::create($request->validated());

        AppModel::saveDeleteImage($firmware_category, $request, ['image']);

        return response()->json([
            'success' => 'Категория успешно добавлена.',
            'redirect' => route('admin.firmware_categories.index')
        ]);
    }

    public function edit(FirmwareCategory $firmware_category)
    {
        abort_if(Gate::denies('firmware_category_edit'), 403, '403 Forbidden');

        return view('admin.firmware_categories.edit', compact('firmware_category'));
    }

    public function update(FirmwareCategoryRequest $request, FirmwareCategory $firmware_category)
    {
        $firmware_category->update($request->validated());

        AppModel::saveDeleteImage($firmware_category, $request, ['image']);

        return response()->json([
            'success' => 'Категория успешно обновлена.',
            'redirect' => route('admin.firmware_categories.index')
        ]);
    }

    public function destroy(FirmwareCategory $firmware_category)
    {
        abort_if(Gate::denies('firmware_category_delete'), 403, '403 Forbidden');

        $firmware_category->delete();

        return back()->with('status', 'Категория успешно удалена.');
    }
}
