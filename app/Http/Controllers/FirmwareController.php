<?php

namespace App\Http\Controllers;

use Meta;
use Validator;
use Notification;
use App\Models\Firmware;
use Illuminate\Http\Request;
use App\Models\FirmwareCategory;
use App\Notifications\FirmwareForm;

class FirmwareController extends Controller
{
    public function index($category = null)
    {
        Meta::includePackages('owl-carousel');

        if ($category) {
            $category = FirmwareCategory::active()->where('alias', $category)->firstOrFail();

            Meta::setTitle($category->meta_title ?: $category->title);
            Meta::setDescription($category->meta_description);
            Meta::setCanonical(route('firmwares.category', $category->alias));
        } else {
            Meta::setCanonical(route('firmwares'));
        }

        $firmwares = Firmware::with('media', 'category')->active()
            ->whereHas('category')->when($category, function ($query) use ($category) {
                $query->where('firmware_category_id', $category->id);
            })
            ->orderByDesc('sort_order')
            ->orderByDesc('id')
            ->paginate(6)->onEachSide(1);

        $firmwares->setPath(url()->current());

        $categories = FirmwareCategory::with('media')->active()->get();

        return view('firmwares.index', compact('firmwares', 'category', 'categories'));
    }

    public function show($category, $alias)
    {
        $firmware = Firmware::active()->whereHas('category', function ($query) use ($category) {
            $query->where('alias', $category);
        })->where('alias', $alias)->firstOrFail();

        static::openGraph($firmware);
        Meta::setTitle($firmware->meta_title ?: $firmware->title);
        Meta::setDescription($firmware->meta_description);
		Meta::includePackages('owl-carousel');

		$related = Firmware::with('media', 'category')->active()->whereHas('category', function ($query) use ($category) {
            $query->where('alias', $category);
        })->limit(8)->get();

        return view('firmwares.show', compact('firmware', 'related'));
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
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

        Notification::route('mail', env('MAIL_TO'))->notify(new FirmwareForm($validator->validated()));

        return response()->json([
            'success' => __('firmwares.success')
        ]);
    }
}
