<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:200', Rule::unique('users', 'email')],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users', 'telefono')],
            'passport_number' => ['required', 'string', 'max:30', Rule::unique('pasajero', 'pasaporte')],
            'nationality' => ['required', 'string', 'max:80'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}