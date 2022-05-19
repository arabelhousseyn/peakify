<?php

namespace App\Http\Requests;

use App\Rules\CheckTypeDefaultCity;
use App\Rules\CheckTypeShipperRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShipperCityRequest extends FormRequest
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
            'price' => 'numeric',
            'city_id' => 'exists:cities,_id'
        ];
    }
}
