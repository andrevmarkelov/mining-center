<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Astrotomic\Translatable\Validation\RuleFactory;

class NewsRequest extends FormRequest
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
        abort_if(Gate::denies('news_' . $type), 403, '403 Forbidden');

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
            'alias' => Rule::unique('news', 'alias')->ignore($this->news),
            'status' => 'required|in:0,1',
            'sitemap' => 'required|in:0,1',
            'publish_from' => 'date',
            'sort_order' => 'integer',
            '%title%' => 'nullable|min:3|max:191',
            '%description%' => 'nullable',
            '%meta_title%' => 'required_with:%title%',
            '%meta_description%' => 'required_with:%title%',
            'categories' => 'array|exists:App\Models\NewsCategory,id',
        ]);
    }
}
