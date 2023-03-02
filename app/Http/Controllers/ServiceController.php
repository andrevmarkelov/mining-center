<?php

namespace App\Http\Controllers;

use Meta;
use App\Models\City;
use App\Models\Country;
use App\Models\Service;
use App\ViewModels\ServiceViewModel;
use Route;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('media', 'cities')
            ->active()
            ->orderByDesc('sort_order')->orderByDesc('id')
            ->paginate(9)->onEachSide(1);

        $services->setPath(url()->current());

        if (request('show_map')) {
            Meta::setCanonical(route('services'));

            return view('services.category', [
                'map_items' => ServiceViewModel::indexMapItems(),
            ]);
        }

        return view('services.index', [
            'services' => $services,
            'countries' => Country::active()->with('cities')->has('cities.services')->get(),
        ]);
    }

    public function category($alias)
    {
        if (Route::is('services.country')) {
            $category = Country::active()->where('alias', $alias)->firstOrFail();

            $map_items = ServiceViewModel::countryMapItems($category);
        } else {
            $category = City::active()->where('alias', $alias)->firstOrFail();

            $map_items = ServiceViewModel::cityMapItems($category);

            $services = Service::with('media', 'meta', 'cities')->active()
                ->whereHas('cities', function ($q) use ($category) {
                    $q->where('city_id', $category->id);
                })
                ->orderByDesc('sort_order')->orderByDesc('id')->get();
        }

        $metadata = $category->getMeta('services');

        $meta_title = $metadata[app()->getLocale()]['meta_title'] ?? '';

        Meta::setTitle($meta_title ?: $category->title);
        Meta::setDescription($metadata[app()->getLocale()]['meta_description'] ?? '');

        return view('services.category', compact('category', 'map_items') + [
            'services' => $services ?? null,
            'page_title' => $metadata[app()->getLocale()]['meta_h1'] ?? '',
            'page_subtitle' => $metadata[app()->getLocale()]['subtitle'] ?? '',
            'page_description' => $metadata[app()->getLocale()]['description'] ?? '',
        ]);
    }

    public function show($alias)
    {
        $service = Service::active()->where('alias', $alias)->firstOrFail();

        static::openGraph($service);
        Meta::setTitle($service->meta_title ?: $service->title);
        Meta::setDescription($service->meta_description);
        Meta::includePackages('owl-carousel');

        $related = Service::with('media')->active()->limit(8)->get();

        return view('services.show', compact('service', 'related'));
    }
}
