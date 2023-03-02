<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;
use App\Models\AppModel;
use App\Models\Coin;
use App\Models\Rating;

class RatingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('rating_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $ratings = Rating::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($ratings)
                ->editColumn('created_at', function($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                ->addColumn('thumb', function($data) {
                    return $data->image;
                })
                ->addColumn('action', function($data) {
                    return view('admin.components.action', [
                        'route' => 'ratings',
                        'can' => 'rating',
                        'id' => $data->id,
                    ]);
                })
                ->toJson();
        }

        return view('admin.ratings.index');
    }

    public function create()
    {
        abort_if(Gate::denies('rating_create'), 403, '403 Forbidden');

        return view('admin.ratings.create', [
            'coins' => Coin::active()->pluck('title', 'id')
        ]);
    }

    public function store(RatingRequest $request)
    {
        $rating = Rating::create($request->validated());

        $rating->coins()->sync($request->input('coins', []));

        AppModel::saveDeleteImage($rating, $request, ['image']);

        return response()->json([
            'success' => 'Пул успешно добавлен.',
            'redirect' => route('admin.ratings.index')
        ]);
    }

    public function edit(Rating $rating)
    {
        abort_if(Gate::denies('rating_edit'), 403, '403 Forbidden');

        $coins = Coin::active()->pluck('title', 'id');

        return view('admin.ratings.edit', compact('rating', 'coins'));
    }

    public function update(RatingRequest $request, Rating $rating)
    {
        $rating->update($request->validated());

        $rating->coins()->sync($request->input('coins', []));

        AppModel::saveDeleteImage($rating, $request, ['image']);

        return response()->json([
            'success' => 'Пул успешно обновлен.',
            'redirect' => route('admin.ratings.index')
        ]);
    }

    public function destroy(Rating $rating)
    {
        abort_if(Gate::denies('rating_delete'), 403, '403 Forbidden');

        $rating->delete();

        return back()->with('status', 'Пул успешно удален.');
    }
}
