<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Astrotomic\Translatable\Validation\RuleFactory;

class WikiRequest extends FormRequest
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
        abort_if(Gate::denies('wiki_' . $type), 403, '403 Forbidden');

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
            'wiki_category_id' => 'required|integer|exists:App\Models\WikiCategory,id',
            'image' => config('image.rule'),
            'alias' => Rule::unique('wiki', 'alias')->ignore($this->wiki),
            'status' => 'required|in:0,1',
            'sitemap' => 'required|in:0,1',
            '%title%' => 'nullable|min:3|max:191',
            '%description%' => 'nullable',
            '%meta_title%' => 'nullable',
            '%meta_description%' => 'nullable',
        ]);
    }
}
