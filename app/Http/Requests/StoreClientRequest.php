<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'full_name' => 'required|max:255',
            'phone' => 'required|digits:10|unique:clients,phone',
            'email' => 'email:rfc,dns,filter|unique:clients,email',
            'city_id' => 'required|exists:cities,_id',
            'address' => 'max:255'
        ];
    }
}
