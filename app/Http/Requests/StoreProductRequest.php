<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,_id',
            'product_code' => 'required|max:255',
            'product_name' => 'required|max:255',
            'description' => 'max:500',
            'price' => 'required|numeric',
            'offers' => 'array',
            'offers.*.quantity' => 'required',
            'offers.*.discount' => 'required',
            'offers.*.is_static' => 'required',
            'variants' => 'array',
            'variants.*.code' => 'required',
            'variants.*.price' => 'required|numeric'
        ];
    }
}
