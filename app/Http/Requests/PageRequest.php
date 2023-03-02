<?php

namespace App\Http\Requests;

use Gate;
use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
        abort_if(Gate::denies('page_' . $type), 403, '403 Forbidden');

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
            '%title%' => 'required|min:3|max:191',
            '%subtitle%' => 'nullable',
            '%description%' => 'nullable',
            '%meta_title%' => 'nullable',
            '%meta_description%' => 'nullable',
            'type' => 'required|string|min:2|in:' . implode(',', config('app_data.page_types')),
            'alias' => Rule::unique('pages', 'alias')->ignore($this->page),
            'status' => 'required|in:0,1',
        ]);
    }
}
