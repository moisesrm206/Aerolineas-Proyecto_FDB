<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRequest;
use App\Models\Pasajero;
use App\Models\Tripulacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(UpdateRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $data = $request->validated();

        if (! empty($data['password'])) {
            if (! Hash::check($data['current_password'], $user->contrasenna)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no coincide.']);
            }

            $user->contrasenna = Hash::make($data['password']);
        }

        DB::transaction(function () use ($user, $data): void {
            $user->nombre = $data['name'];
            $user->email = $data['email'];
            $user->telefono = $data['phone'] ?? null;
            $user->save();

            if ($user->rol === 'pasajero') {
                Pasajero::updateOrCreate(
                    ['id_user' => $user->id_user],
                    [                        
                        'pasaporte' => $data['passport_number'],
                        'nacionalidad' => $data['nationality'],
                    ]
                );
            }

            if ($user->rol === 'tripulacion') {
                Tripulacion::updateOrCreate(
                    ['id_user' => $user->id_user],
                    [
                        'num_licencia' => $data['license_number'],
                    ]
                );
            }
        });

        return back()->with('status', 'Cuenta actualizada correctamente.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = $request->user();

        if (! Hash::check($request->input('current_password'), $user->contrasenna)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no coincide.']);
        }

        DB::transaction(function () use ($user): void {
            if ($user->rol === 'pasajero' && $user->pasajero) {
                $user->pasajero->update(['id_user' => null]);
            }

            if ($user->rol === 'tripulacion' && $user->tripulacion) {
                $user->tripulacion->update(['id_user' => null]);
            }

            User::destroy($user->id_user);
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }
}
