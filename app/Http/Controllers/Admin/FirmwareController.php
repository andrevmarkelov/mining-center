<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\FirmwareRequest;
use App\Models\AppModel;
use App\Models\Firmware;
use App\Models\FirmwareCategory;

class FirmwareController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('firmware_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $firmwares = Firmware::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($firmwares)
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
                        'route' => 'firmwares',
                        'can' => 'firmware',
                        'id' => $data->id,
                    ]);
                })
                ->toJson();
        }

        return view('admin.firmwares.index');
    }

    public function create()
    {
        abort_if(Gate::denies('firmware_create'), 403, '403 Forbidden');

        return view('admin.firmwares.create', [
            'categories' => FirmwareCategory::active()->pluck('title', 'id')
        ]);
    }

    public function store(FirmwareRequest $request)
    {
        $firmware = Firmware::create($request->validated());

        AppModel::saveDeleteImage($firmware, $request, ['image']);

        if ($attach = $request->input('attach')) {
            foreach ($attach as $item) {
                $firmware->addMedia(\Storage::path($item))->toMediaCollection('attach');
            }
            \Storage::deleteDirectory('tmp');
        }

        return response()->json([
            'success' => 'Прошивка успешно добавлена.',
            'redirect' => route('admin.firmwares.index')
        ]);
    }

    public function edit(Firmware $firmware)
    {
        abort_if(Gate::denies('firmware_edit'), 403, '403 Forbidden');

        $categories = FirmwareCategory::active()->pluck('title', 'id');

        return view('admin.firmwares.edit', compact('firmware', 'categories'));
    }

    public function update(FirmwareRequest $request, Firmware $firmware)
    {
        $firmware->update($request->validated());

        AppModel::saveDeleteImage($firmware, $request, ['image']);

        if ($request->has('attach')) {
            $attach = array_filter($request->input('attach'), function($value) {
                return !is_null($value) && $value !== '';
            });

            if ($attach) {
                foreach ($attach as $item) {
                    $firmware->addMedia(\Storage::path($item))->toMediaCollection('attach');
                }
                \Storage::deleteDirectory('tmp');
            }
        }

        return response()->json([
            'success' => 'Прошивка успешно обновлена.',
            'redirect' => route('admin.firmwares.index')
        ]);
    }

    public function destroy(Firmware $firmware)
    {
        abort_if(Gate::denies('firmware_delete'), 403, '403 Forbidden');

        $firmware->delete();

        return back()->with('status', 'Прошивка успешно удалена.');
    }
}
