<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use App\Notifications\RatingForm;
use Illuminate\Http\Request;
use Meta;
use Notification;
use Validator;

class RatingController extends Controller
{
    public function show($alias)
    {
        $coin = Coin::with(['ratings.media', 'ratings' => function($query) {
            $query->orderBy('hashrate', 'desc');
        }])->active()->where('alias', $alias)->firstOrFail();

        static::openGraph($coin);
        Meta::setTitle($coin->meta_title ?: $coin->title);
        Meta::setDescription($coin->meta_description);
        Meta::includePackages('datatables', 'fixedcolumns');

        return view('ratings.show', compact('coin'));
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'contact' => 'required|min:2',
            'pool' => 'nullable|min:2',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'errors' => $errors->all()
            ]);
        }

        Notification::route('mail', env('MAIL_TO'))->notify(new RatingForm($validator->validated()));

        return response()->json([
            'success' => __('ratings.success')
        ]);
    }
}
