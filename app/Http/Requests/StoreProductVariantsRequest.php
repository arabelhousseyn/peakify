<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantsRequest extends FormRequest
{
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
        return [
            'product_id' => 'required|exists:products,_id',
            'variants' => 'array|required',
            'variants.*.code' => 'required',
            'variants.*.price' => 'required|numeric',
            'variants.*.options' => 'required|array',
            'variants.*.options.*.option_value_id' => 'required'
        ];
    }
}
