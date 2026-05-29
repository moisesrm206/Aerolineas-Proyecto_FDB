<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        $user = $this->user();
        $userId = $user?->id_user;
        $pasajeroId = $user?->pasajero?->id_pasajero;
        $tripulacionId = $user?->tripulacion?->id_tripulacion;

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:200',
                Rule::unique('users', 'email')->ignore($userId, 'id_user'),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'telefono')->ignore($userId, 'id_user'),
            ],
            'passport_number' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('pasajero', 'pasaporte')->ignore($pasajeroId, 'id_pasajero'),
            ],
            'nationality' => ['nullable', 'string', 'max:80'],
            'license_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('tripulacion', 'num_licencia')->ignore($tripulacionId, 'id_tripulacion'),
            ],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ];
    }
}