<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyLoginCodeRequest extends FormRequest
{
    public function rules(): array
    {
        // login code is numeric and with 6 digits
        return [
            "login_code" => ["required", "numeric", "digits:6"],
        ];
    }

    public function messages(): array
    {
        // error messages are made same to avoid disclosing
        // the length required and type of code
        return [
            "login_code.numeric" => "The provided login_code is invalid",
            "login_code.digits" => "The provided login_code is invalid",
        ];
    }
}
