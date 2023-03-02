<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Astrotomic\Translatable\Validation\RuleFactory;

class EquipmentRequest extends FormRequest
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
        abort_if(Gate::denies('equipment_' . $type), 403, '403 Forbidden');

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
            'price' => 'nullable|numeric',
            'available' => 'nullable|in:0,1',
            'alias' => Rule::unique('equipments', 'alias')->ignore($this->equipment),
            'status' => 'required|in:0,1',
            'sitemap' => 'required|in:0,1',
            'coin_id' => 'required|integer|exists:App\Models\Coin,id',
            'firmware_id' => 'nullable|integer|exists:App\Models\Firmware,id',
            'manufacturer_id' => 'nullable|integer|exists:App\Models\Manufacturer,id',
            'hashrate' => 'required|numeric|not_in:0',
            'power' => 'required|numeric|not_in:0',
            '%title%' => 'required|min:3|max:191',
            '%add_title%' => 'nullable|min:3|max:191',
            '%description%' => 'nullable',
            '%add_description%' => 'nullable',
            '%meta_title%' => 'nullable',
            '%meta_description%' => 'nullable',
        ]);
    }
}
