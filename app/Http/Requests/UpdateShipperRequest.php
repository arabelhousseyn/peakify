<?php

namespace App\Http\Requests;

use App\Rules\CheckTypeShipperRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShipperRequest extends FormRequest
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
            'phone' => 'digits:10',
            'email' => 'email:rfc,dns,filter',
            'type' => [new CheckTypeShipperRule()]
        ];
    }
}
