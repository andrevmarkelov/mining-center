<?php

namespace App\Http\Requests;

use Gate;
use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WikiCategoryRequest extends FormRequest
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
        abort_if(Gate::denies('wiki_category_' . $type), 403, '403 Forbidden');

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
            'alias' => Rule::unique('wiki_categories', 'alias')->ignore($this->wiki_category),
            'status' => 'required|in:0,1',
            'sitemap' => 'required|in:0,1',
            '%title%' => 'required|min:3|max:191',
            '%subtitle%' => 'nullable',
            '%description%' => 'nullable',
            '%meta_h1%' => 'nullable',
            '%meta_title%' => 'nullable',
            '%meta_description%' => 'nullable',
        ]);
    }
}
