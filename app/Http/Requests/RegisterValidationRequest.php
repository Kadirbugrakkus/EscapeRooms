<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterValidationRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'name' => 'required',
            'identity_number' => 'required|unique:users|digits:11',
            'birth_day' => 'required|date|before_or_equal:today',
            'phone' => 'required|unique:users|digits:10',
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',              // En az 8 karakter uzunluğunda olmalı
                'regex:/[A-Z]/',      // En az bir büyük harf içermeli
                'regex:/[a-z]/',      // En az bir küçük harf içermeli
                'regex:/[0-9]/',      // En az bir sayı içermeli
            ],

        ];
    }
}
