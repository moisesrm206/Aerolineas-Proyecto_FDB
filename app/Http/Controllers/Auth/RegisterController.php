<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Pasajero;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('access.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'nombre' => $data['name'],
                'email' => $data['email'],
                'telefono' => $data['phone'] ?? null,
                'contrasenna' => Hash::make($data['password']),
                'rol' => 'pasajero',
            ]);

            Pasajero::create([
                'id_user' => $user->id_user,
                'pasaporte' => $data['passport_number'],
                'nacionalidad' => $data['nationality'],
            ]);

            return $user;
        });

        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->route('panel.principal');
    }
}
