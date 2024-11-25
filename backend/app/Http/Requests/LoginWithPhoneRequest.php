<?php

namespace App\Http\Requests;

use App\Rules\CountryPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class LoginWithPhoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // If Required is not set here then CountryPhoneNumber Validator will not run
        // Required is required here

        return [
            'phone' => ['required', new CountryPhoneNumber],
        ];
    }
}
