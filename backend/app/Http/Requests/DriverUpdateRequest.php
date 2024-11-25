<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverUpdateRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'min:1'],
            'make' => ['required', 'string', 'min:1'],
            'model' => ['required', 'string', 'min:1'],
            'color' => ['required', 'string', 'min:1'],
            'license_plate' => ['required', 'string', 'min:1'],
        ];
    }
}
