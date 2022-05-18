<?php

namespace App\Http\Requests;

use App\Rules\CheckTypeDefaultCity;
use App\Rules\CheckTypeShipperRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreShipperRequest extends FormRequest
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
            'full_name' => 'required',
            'phone' => 'required|digits:10',
            'email' => 'required|email:rfc,dns,filter',
            'type' => ['required', new CheckTypeShipperRule()],
            'cities' => 'array',
            'cities.*.city_id' => 'required|exists:cities,_id',
            'cities.*.price' => 'required|numeric',
            'cities.*.type' => ['required',new CheckTypeDefaultCity()]
        ];
    }
}
