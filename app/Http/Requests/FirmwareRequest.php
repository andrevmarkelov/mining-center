<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Astrotomic\Translatable\Validation\RuleFactory;

class FirmwareRequest extends FormRequest
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
        abort_if(Gate::denies('firmware_' . $type), 403, '403 Forbidden');

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
            'firmware_category_id' => 'required|integer|exists:App\Models\FirmwareCategory,id',
            'image' => config('image.rule'),
            'alias' => Rule::unique('firmwares', 'alias')->ignore($this->firmware),
            'status' => 'required|in:0,1',
            'sitemap' => 'required|in:0,1',
            'sort_order' => 'integer',
            '%title%' => 'required|min:3|max:191',
            '%description%' => 'nullable',
            '%add_description%' => 'nullable',
            '%meta_title%' => 'nullable',
            '%meta_description%' => 'nullable',
        ]);
    }
}
