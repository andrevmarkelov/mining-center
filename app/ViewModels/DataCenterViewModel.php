<?php

namespace App\ViewModels;

use App\Models\City;
use App\Models\Country;
use Route;

class DataCenterViewModel
{
    public static function indexMapItems()
    {
        return self::formattingMapItems(Country::active()
            ->has('cities.dataCenters')
            ->withCount(['cities as data_centers_count' => function ($query) {
                $query->leftJoin('city_data_center', 'cities.id', 'city_data_center.city_id');
            }])
            ->get());
    }

    public static function countryMapItems($country)
    {
        return self::formattingMapItems(City::active()
            ->has('dataCenters')
            ->withCount('dataCenters')
            ->where('country_id', $country->id)
            ->get());
    }

    public static function cityMapItems($city)
    {
        return self::formattingMapItems(City::active()
            ->has('dataCenters')
            ->withCount('dataCenters')
            ->where('id', $city->id)
            ->get());
    }

    protected static function formattingMapItems($object)
    {
        return $object->keyBy('id')->map(function ($item) {
            if ($item->latitude && $item->longitude) {
                $route = Route::is('data_centers') ? 'country' : 'city';

                return [
                    'lat' => $item->latitude,
                    'lng' => $item->longitude,
                    'count' => $item->data_centers_count,
                    'link' => route('data_centers.' . $route, $item->alias)
                ];
            }
        })->toArray();
    }
}
