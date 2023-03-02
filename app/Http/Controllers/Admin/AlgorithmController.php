<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\AlgorithmRequest;
use App\Models\Algorithm;

class AlgorithmController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('algorithm_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $algorithms = Algorithm::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($algorithms)
                ->editColumn('created_at', function($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                ->addColumn('action', function($data) {
                    return view('admin.components.action', [
                        'route' => 'algorithms',
                        'can' => 'algorithm',
                        'id' => $data->id,
                    ]);
                })
                ->toJson();
        }

        return view('admin.algorithms.index');
    }

    public function create()
    {
        abort_if(Gate::denies('algorithm_create'), 403, '403 Forbidden');

        return view('admin.algorithms.create');
    }

    public function store(AlgorithmRequest $request)
    {
        Algorithm::create($request->validated());

        return response()->json([
            'success' => 'Алгоритм успешно добавлен.',
            'redirect' => route('admin.algorithms.index')
        ]);
    }

    public function edit(Algorithm $algorithm)
    {
        abort_if(Gate::denies('algorithm_edit'), 403, '403 Forbidden');

        return view('admin.algorithms.edit', compact('algorithm'));
    }

    public function update(AlgorithmRequest $request, Algorithm $algorithm)
    {
        $algorithm->update($request->validated());

        return response()->json([
            'success' => 'Алгоритм успешно обновлен.',
            'redirect' => route('admin.algorithms.index')
        ]);
    }

    public function destroy(Algorithm $algorithm)
    {
        abort_if(Gate::denies('algorithm_delete'), 403, '403 Forbidden');

        $algorithm->delete();

        return back()->with('status', 'Алгоритм успешно удален.');
    }
}
