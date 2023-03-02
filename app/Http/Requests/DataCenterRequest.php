<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Astrotomic\Translatable\Validation\RuleFactory;

class DataCenterRequest extends FormRequest
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
        abort_if(Gate::denies('data_center_' . $type), 403, '403 Forbidden');

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
            // 'countries' => 'required|array|min:1|exists:App\Models\Country,id',
            'cities' => 'required|array|min:1|exists:App\Models\City,id',
            'image' => config('image.rule'),
            'power_type' => 'required|in:' . implode(',', array_flip(__('common.power_type'))),
            'alias' => Rule::unique('data_centers', 'alias')->ignore($this->data_center),
            'sort_order' => 'integer',
            'status' => 'required|in:0,1',
            'sitemap' => 'required|in:0,1',
            'contacts' => 'array',
            'show_contacts' => 'in:0,1',
            'is_partner' => 'in:0,1',
            '%title%' => 'required|min:3|max:191',
            '%description%' => 'nullable',
            '%add_description%' => 'nullable',
            '%meta_title%' => 'nullable',
            '%meta_description%' => 'nullable',
            '%action_text%' => 'nullable',
        ]);
    }
}
