<?php

namespace App\Http\Controllers;

use App\Notifications\ContactForm;
use Illuminate\Http\Request;
use Notification;
use Validator;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacts.index');
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'telegram' => 'nullable|min:2',
            'comment' => 'nullable|min:5',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'errors' => $errors->all()
            ]);
        }

        Notification::route('mail', env('MAIL_TO'))->notify(new ContactForm($validator->validated()));

        return response()->json([
            'success' => __('contacts.success')
        ]);
    }
}
