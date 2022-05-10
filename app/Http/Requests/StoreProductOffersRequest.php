<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductOffersRequest extends FormRequest
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
            'offers' => 'array|required',
            'offers.*.quantity' => 'required|numeric',
            'offers.*.discount' => 'required|numeric',
            'offers.*.is_static' => 'required|boolean',
        ];
    }
}
