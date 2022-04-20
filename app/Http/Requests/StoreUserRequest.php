<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|email:rfc,dns,filter|unique:users,email',
            'username' => 'required|max:255|unique:users,username',
            'password' => ['required','confirmed',Password::default()],
            'phone' => 'digits:10',
            'job' => 'max:255',
        ];
    }
}
