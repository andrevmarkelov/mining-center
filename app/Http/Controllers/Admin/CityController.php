<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\City;
use App\Models\Country;
use LaravelLocalization;
use Illuminate\Http\Request;
use App\Http\Requests\CityRequest;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('city_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $cities = City::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($cities)
                ->filterColumn('title', function($query, $keyword) {
                    $query->whereTranslationLike('title', "%{$keyword}%", \App::getLocale());
                })
                ->editColumn('created_at', function($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                ->editColumn('title', function($data) {
                    return $data->title . '<br>Metadata:
                    <a href="' . route('admin.city_metadata.edit', ['city' => $data, 'type' => 'data_centers']) . '">Дата центр</a>,
                    <a href="' . route('admin.city_metadata.edit', ['city' => $data, 'type' => 'services']) . '">Сервис центры</a>';
                })
                ->addColumn('action', function($data) {
                    return view('admin.components.action', [
                        'route' => 'cities',
                        'can' => 'city',
                        'id' => $data->id,
                    ]);
                })
                ->rawColumns(['title'])
                ->toJson();
        }

        return view('admin.cities.index');
    }

    public function create()
    {
        abort_if(Gate::denies('city_create'), 403, '403 Forbidden');

        return view('admin.cities.create', [
            'countries' => Country::active()->listsTranslations('title')->pluck('title', 'id')
        ]);
    }

    public function store(CityRequest $request)
    {
        City::create($request->validated());

        return response()->json([
            'success' => 'Город успешно добавлен.',
            'redirect' => route('admin.cities.index')
        ]);
    }

    public function edit(City $city)
    {
        abort_if(Gate::denies('city_edit'), 403, '403 Forbidden');

        return view('admin.cities.edit', [
            'city' => $city,
            'countries' => Country::active()->listsTranslations('title')->pluck('title', 'id')
        ]);
    }

    public function update(CityRequest $request, City $city)
    {
        $city->update($request->validated());

        return response()->json([
            'success' => 'Город успешно обновлен.',
            'redirect' => route('admin.cities.index')
        ]);
    }

    public function destroy(City $city)
    {
        abort_if(Gate::denies('city_delete'), 403, '403 Forbidden');

        $city->delete();

        return back()->with('status', 'Город успешно удален.');
    }

    public function editMetadata(City $city)
    {
        $type = $this->metadataEditAccess();

        $data['sitemap'] = $city->getMeta($type)['sitemap'] ?? '1';

        foreach (LaravelLocalization::getSupportedLocales() as $key => $item) {
            $data[$key] = [
                'description' => $city->getMeta($type)[$key]['description'] ?? '',
                'meta_h1' => $city->getMeta($type)[$key]['meta_h1'] ?? '',
                'subtitle' => $city->getMeta($type)[$key]['subtitle'] ?? '',
                'meta_title' => $city->getMeta($type)[$key]['meta_title'] ?? '',
                'meta_description' => $city->getMeta($type)[$key]['meta_description'] ?? '',
            ];
        }

        return view('admin.cities.edit_metadata', compact('city', 'data'));
    }

    public function updateMetadata(Request $request, City $city)
    {
        $type = $this->metadataEditAccess();

        $city->syncMeta([$type => $request->input('data')] + $city->getAllMeta()->toArray());

        return response()->json([
            'success' => 'Метаданные успешно обновлены.',
            'redirect' => route('admin.cities.index')
        ]);
    }

	protected function metadataEditAccess()
	{
		abort_if(!in_array($type = request('type'), ['data_centers', 'services']), 404);

		switch ($type) {
			case 'data_centers':
				abort_if(Gate::denies('data_center_edit'), 403, '403 Forbidden');
            case 'services':
                abort_if(Gate::denies('service_edit'), 403, '403 Forbidden');
		}

		return $type;
	}
}
