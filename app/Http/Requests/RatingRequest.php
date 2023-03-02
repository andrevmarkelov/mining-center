<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RatingRequest extends FormRequest
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
        abort_if(Gate::denies('rating_' . $type), 403, '403 Forbidden');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => config('image.rule'),
            'title' => 'required|min:3|max:191',
            'link' => 'required|min:3|max:191',
            'ref_link' => 'nullable|min:3|max:191',
            'review_link' => 'nullable|min:3|max:191',
            'status' => 'required|in:0,1',
            'coins' => 'required|array|exists:App\Models\Coin,id',
        ];
    }
}
