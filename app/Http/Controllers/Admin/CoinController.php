<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\CoinRequest;
use Illuminate\Http\Request;
use App\Models\Algorithm;
use App\Models\AppModel;
use App\Models\Coin;
use LaravelLocalization;

class CoinController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('coin_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $coins = Coin::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($coins)
                ->editColumn('created_at', function($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                ->editColumn('title', function($data) {
                    return $data->title . '<br>Metadata:
                    <a href="' . route('admin.coin_metadata.edit', ['coin' => $data, 'type' => 'ratings']) . '">Рейтинг</a>,
                    <a href="' . route('admin.coin_metadata.edit', ['coin' => $data, 'type' => 'equipments']) . '">Оборудование</a>,
                    <a href="' . route('admin.coin_metadata.edit', ['coin' => $data, 'type' => 'crypto_calc']) . '">Калькулятор</a>';
                })
                ->addColumn('thumb', function($data) {
                    return $data->image;
                })
                ->addColumn('action', function($data) {
                    return view('admin.components.action', [
                        'route' => 'coins',
                        'can' => 'coin',
                        'id' => $data->id,
                    ]);
                })
                ->rawColumns(['title'])
                ->toJson();
        }

        return view('admin.coins.index');
    }

    public function create()
    {
        abort_if(Gate::denies('coin_create'), 403, '403 Forbidden');

        return view('admin.coins.create', [
            'algorithms' => Algorithm::pluck('title', 'id')
        ]);
    }

    public function store(CoinRequest $request)
    {
        $coin = Coin::create($request->validated());

        AppModel::saveDeleteImage($coin, $request, ['image']);

        return response()->json([
            'success' => 'Монета успешно добавлена.',
            'redirect' => route('admin.coins.index')
        ]);
    }

    public function edit(Coin $coin)
    {
        abort_if(Gate::denies('coin_edit'), 403, '403 Forbidden');

        return view('admin.coins.edit', [
            'coin' => $coin,
            'algorithms' => Algorithm::pluck('title', 'id')
        ]);
    }

    public function update(CoinRequest $request, Coin $coin)
    {
        $coin->update($request->validated());

        AppModel::saveDeleteImage($coin, $request, ['image']);

        return response()->json([
            'success' => 'Монета успешно обновлена.',
            'redirect' => route('admin.coins.index')
        ]);
    }

    public function destroy(Coin $coin)
    {
        abort_if(Gate::denies('coin_delete'), 403, '403 Forbidden');

        $coin->delete();

        return back()->with('status', 'Монета успешно удалена.');
    }

    public function editMetadata(Coin $coin)
    {
        $type = $this->metadataEditAccess();

        $data = [];

        if ($type == 'ratings') {
            $data['sitemap'] = $coin->sitemap;

            foreach (LaravelLocalization::getSupportedLocales() as $key => $item) {
                $data[$key] = [
                    'description' => $coin->translate($key)->description ?? '',
                    'meta_h1' => $coin->translate($key)->meta_h1 ?? '',
                    'subtitle' => $coin->translate($key)->subtitle ?? '',
                    'meta_title' => $coin->translate($key)->meta_title ?? '',
                    'meta_description' => $coin->translate($key)->meta_description ?? '',
                ];
            }
        } else {
            $data['sitemap'] = $coin->getMeta($type)['sitemap'] ?? '1';

            foreach (LaravelLocalization::getSupportedLocales() as $key => $item) {
                $data[$key] = [
                    'description' => $coin->getMeta($type)[$key]['description'] ?? '',
                    'meta_h1' => $coin->getMeta($type)[$key]['meta_h1'] ?? '',
                    'subtitle' => $coin->getMeta($type)[$key]['subtitle'] ?? '',
                    'meta_title' => $coin->getMeta($type)[$key]['meta_title'] ?? '',
                    'meta_description' => $coin->getMeta($type)[$key]['meta_description'] ?? '',
                ];
            }
        }

        return view('admin.coins.edit_metadata', compact('coin', 'data'));
    }

    public function updateMetadata(Request $request, Coin $coin)
    {
        $type = $this->metadataEditAccess();

        if ($type == 'ratings') {
            $coin->update($request->input('data'));
        } else {
            $coin->syncMeta([$type => $request->input('data')] + $coin->getAllMeta()->toArray());
        }

        return response()->json([
            'success' => 'Метаданные успешно обновлены.',
            'redirect' => route('admin.coins.index')
        ]);
    }

	protected function metadataEditAccess()
	{
		abort_if(!in_array($type = request('type'), ['ratings', 'equipments', 'crypto_calc']), 404);

		switch ($type) {
			case 'ratings':
				abort_if(Gate::denies('rating_edit'), 403, '403 Forbidden');
			case 'equipments':
				abort_if(Gate::denies('equipment_edit'), 403, '403 Forbidden');
		}

		return $type;
	}
}
