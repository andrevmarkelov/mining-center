<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Astrotomic\Translatable\Validation\RuleFactory;

class ServiceRequest extends FormRequest
{
    use ReturnJsonFailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'cities' => 'required|array|min:1|exists:App\Models\City,id',
            'image' => config('image.rule'),
            'equipment_type' => 'required|in:' . implode(',', array_flip(__('common.equipment_type'))),
            'alias' => Rule::unique('services', 'alias')->ignore($this->service),
            'contacts' => 'array',
            'sort_order' => 'integer',
            'status' => 'required|in:0,1',
            'sitemap' => 'required|in:0,1',
            '%title%' => 'required|min:3|max:191',
            '%description%' => 'nullable',
            '%meta_title%' => 'nullable',
            '%meta_description%' => 'nullable',
        ]);
    }
}
