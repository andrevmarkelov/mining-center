<?php

namespace App\ViewModels;

use App\Models\City;
use App\Models\Country;
use Route;

class ServiceViewModel
{
    public static function indexMapItems()
    {
        return self::formattingMapItems(Country::active()
            ->has('cities.services')
            ->withCount(['cities as services_count' => function ($query) {
                $query->leftJoin('city_service', 'cities.id', 'city_service.city_id');
            }])
            ->get());
    }

    public static function countryMapItems($country)
    {
        return self::formattingMapItems(City::active()
            ->has('services')
            ->withCount('services')
            ->where('country_id', $country->id)
            ->get());
    }

    public static function cityMapItems($city)
    {
        return self::formattingMapItems(City::active()
            ->has('services')
            ->withCount('services')
            ->where('id', $city->id)
            ->get());
    }

    protected static function formattingMapItems($object)
    {
        return $object->keyBy('id')->map(function ($item) {
            if ($item->latitude && $item->longitude) {
                $route = Route::is('services') ? 'country' : 'city';

                return [
                    'lat' => $item->latitude,
                    'lng' => $item->longitude,
                    'count' => $item->services_count,
                    'link' => route('services.' . $route, $item->alias)
                ];
            }
        })->toArray();
    }
}
