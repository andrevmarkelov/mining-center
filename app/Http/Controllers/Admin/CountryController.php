<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use Illuminate\Http\Request;
use App\Models\Country;
use LaravelLocalization;

class CountryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('country_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $countries = Country::when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($countries)
                ->filterColumn('title', function($query, $keyword) {
                    $query->whereTranslationLike('title', "%{$keyword}%", \App::getLocale());
                })
                ->editColumn('created_at', function($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                ->editColumn('title', function($data) {
                    return $data->title . '<br>Metadata:
                    <a href="' . route('admin.country_metadata.edit', ['country' => $data, 'type' => 'data_centers']) . '">Дата центр</a>,
                    <a href="' . route('admin.country_metadata.edit', ['country' => $data, 'type' => 'services']) . '">Сервис центры</a>';
                })
                ->addColumn('action', function($data) {
                    return view('admin.components.action', [
                        'route' => 'countries',
                        'can' => 'country',
                        'id' => $data->id,
                    ]);
                })
                ->rawColumns(['title'])
                ->toJson();
        }

        return view('admin.countries.index');
    }

    public function create()
    {
        abort_if(Gate::denies('country_create'), 403, '403 Forbidden');

        return view('admin.countries.create');
    }

    public function store(CountryRequest $request)
    {
        Country::create($request->validated());

        return response()->json([
            'success' => 'Страна успешно добавлена.',
            'redirect' => route('admin.countries.index')
        ]);
    }

    public function edit(Country $country)
    {
        abort_if(Gate::denies('country_edit'), 403, '403 Forbidden');

        return view('admin.countries.edit', compact('country'));
    }

    public function update(CountryRequest $request, Country $country)
    {
        $country->update($request->validated());

        return response()->json([
            'success' => 'Страна успешно обновлена.',
            'redirect' => route('admin.countries.index')
        ]);
    }

    public function destroy(Country $country)
    {
        abort_if(Gate::denies('country_delete'), 403, '403 Forbidden');

        $country->delete();

        return back()->with('status', 'Страна успешно удалена.');
    }

    public function editMetadata(Country $country)
    {
        $type = $this->metadataEditAccess();

        $data['sitemap'] = $country->getMeta($type)['sitemap'] ?? '1';

        foreach (LaravelLocalization::getSupportedLocales() as $key => $item) {
            $data[$key] = [
                'description' => $country->getMeta($type)[$key]['description'] ?? '',
                'meta_h1' => $country->getMeta($type)[$key]['meta_h1'] ?? '',
                'subtitle' => $country->getMeta($type)[$key]['subtitle'] ?? '',
                'meta_title' => $country->getMeta($type)[$key]['meta_title'] ?? '',
                'meta_description' => $country->getMeta($type)[$key]['meta_description'] ?? '',
            ];
        }

        return view('admin.countries.edit_metadata', compact('country', 'data'));
    }

    public function updateMetadata(Request $request, Country $country)
    {
        $type = $this->metadataEditAccess();

        $country->syncMeta([$type => $request->input('data')] + $country->getAllMeta()->toArray());

        return response()->json([
            'success' => 'Метаданные успешно обновлены.',
            'redirect' => route('admin.countries.index')
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
