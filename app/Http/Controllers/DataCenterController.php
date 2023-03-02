<?php

namespace App\Http\Controllers;

use Meta;
use Validator;
use Notification;
use App\Models\City;
use App\Models\Country;
use App\Models\DataCenter;
use Illuminate\Http\Request;
use App\Notifications\DataCenterForm;
use App\Notifications\DataCenterSupport;
use App\ViewModels\DataCenterViewModel;
use Route;

class DataCenterController extends Controller
{
    public function index()
    {
        $data_centers = DataCenter::with('media', 'meta', 'cities')
            ->active()
            ->orderByDesc('sort_order')->orderByDesc('id')
            ->paginate(8)->onEachSide(1);

        $data_centers->setPath(url()->current());

        if (request('show_map')) {
            Meta::setCanonical(route('data_centers'));

            return view('data_centers.category', [
                'map_items' => DataCenterViewModel::indexMapItems(),
            ]);
        }

        return view('data_centers.index', [
            'data_centers' => $data_centers,
            'countries' => Country::active()->with('cities')->has('cities.dataCenters')->get(),
        ]);
    }

    public function category($alias)
    {
        if (Route::is('data_centers.country')) {
            $category = Country::active()->where('alias', $alias)->firstOrFail();

            $map_items = DataCenterViewModel::countryMapItems($category);
        } else {
            $category = City::active()->where('alias', $alias)->firstOrFail();

            $map_items = DataCenterViewModel::cityMapItems($category);

            $data_centers = DataCenter::with('media', 'meta', 'cities')->active()
                ->whereHas('cities', function ($q) use ($category) {
                    $q->where('city_id', $category->id);
                })
                ->orderByDesc('sort_order')->orderByDesc('id')->get();
        }

        $metadata = $category->getMeta('data_centers');

        $meta_title = $metadata[app()->getLocale()]['meta_title'] ?? '';

        Meta::setTitle($meta_title ?: $category->title);
        Meta::setDescription($metadata[app()->getLocale()]['meta_description'] ?? '');

        return view('data_centers.category', compact('category', 'map_items') + [
            'data_centers' => $data_centers ?? null,
            'page_title' => $metadata[app()->getLocale()]['meta_h1'] ?? '',
            'page_subtitle' => $metadata[app()->getLocale()]['subtitle'] ?? '',
            'page_description' => $metadata[app()->getLocale()]['description'] ?? '',
        ]);
    }

    public function show($alias)
    {
        $data_center = DataCenter::active()->where('alias', $alias)->firstOrFail();

        static::openGraph($data_center);
        Meta::setTitle($data_center->meta_title ?: $data_center->title);
        Meta::setDescription($data_center->meta_description);
        Meta::includePackages('owl-carousel');

        $related = DataCenter::with('media')->active()->limit(8)->get();

        return view('data_centers.show', compact('data_center', 'related'));
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'location' => 'required|min:2',
            'text' => 'nullable|min:2',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'errors' => $errors->all()
            ]);
        }

        Notification::route('mail', env('MAIL_TO'))->notify(new DataCenterForm($validator->validated()));

        return response()->json([
            'success' => __('data_centers.success')
        ]);
    }

    public function support(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|min:2',
            'email' => 'required|email',
            'telegram' => 'nullable|min:2',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'errors' => $errors->all()
            ]);
        }

        Notification::route('mail', env('MAIL_TO'))->notify(new DataCenterSupport($validator->validated()));

        return response()->json([
            'success' => __('contacts.success')
        ]);
    }
}
