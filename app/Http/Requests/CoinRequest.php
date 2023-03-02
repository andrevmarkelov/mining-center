<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Astrotomic\Translatable\Validation\RuleFactory;

class CoinRequest extends FormRequest
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
        abort_if(Gate::denies('coin_' . $type), 403, '403 Forbidden');

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
            'algorithm_id' => 'required|integer|exists:App\Models\Algorithm,id',
            'title' => 'required|min:3|max:191|' . Rule::unique('coins', 'title')->ignore($this->coin),
            'code' => 'required|min:3|max:10|' . Rule::unique('coins', 'code')->ignore($this->coin),
            'alias' => Rule::unique('coins', 'alias')->ignore($this->coin),
            'show_home' => 'in:0,1',
            'whattomine_coin_id' => 'nullable|integer',
            'status' => 'required|in:0,1',
        ]);
    }
}
