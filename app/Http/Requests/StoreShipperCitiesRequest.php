<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipperCitiesRequest extends FormRequest
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
            "shipper_id" => 'required|exists:shippers,_id',
            'cities' => 'required|array',
            'cities.*.city_id' => 'required|exists:cities,_id',
            'cities.*.price' => 'required|numeric',
        ];
    }
}
