<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Astrotomic\Translatable\Validation\RuleFactory;

class AdvertisingRequest extends FormRequest
{
    use ReturnJsonFailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $type = request()->isMethod('post') ? 'create' : 'edit';
        abort_if(Gate::denies('advertising_' . $type), 403, '403 Forbidden');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return RuleFactory::make([
            'image' => config('image.rule'),
            'type' => 'required|in:' . implode(',', array_flip(config('app_data.advertising_types'))),
            'link' => 'required|min:3|max:191',
            'nofollow' => 'in:0,1',
            'language' => 'required|array',
            'position' => 'required|array',
            'status' => 'required|in:0,1',
        ]);
    }
}
