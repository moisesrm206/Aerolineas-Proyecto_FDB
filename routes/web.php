<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\VueloController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('panel.index');
    }

    return view('public.home');
})->name('home');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');

Route::post('/login', [LoginController::class, 'login'])->name('login.store');

Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::middleware('auth')->group(function () {
    Route::patch('/cuenta', [ProfileController::class, 'update'])->name('account.update');
    Route::delete('/cuenta', [ProfileController::class, 'destroy'])->name('account.destroy');
    Route::post('/cerrar_sesion', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('admin')->group(function () {
        Route::get('/panel', function () {
            abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

            return view('internal.panel', ['user' => Auth::user()]);
        })->name('admin.panel');

        Route::get('/cuenta/nueva', function () {
            abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

            return view('access.register', [
                'modo_admin' => true,
            ]);
        })->name('admin.cuenta.nueva');

        Route::post('/cuenta', function (Request $request) {
            abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

            $data = $request->validate([
                'name' => ['required', 'string', 'max:150'],
                'email' => ['required', 'email', 'max:200', 'unique:users,email'],
                'phone' => ['nullable', 'string', 'max:20', 'unique:users,telefono'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
                'rol' => ['nullable', 'in:admin'],
                'tipo_cuenta' => ['nullable', 'in:admin'],
            ]);

            $user = User::create([
                'nombre' => $data['name'],
                'email' => $data['email'],
                'telefono' => $data['phone'] ?? null,
                'contrasenna' => Hash::make($data['password']),
                'rol' => $data['rol'] ?? $data['tipo_cuenta'] ?? 'admin',
            ]);

            return redirect()->route('admin.panel');
        })->name('admin.cuenta.store');
    });

    Route::prefix('personal')->group(function () {
        Route::get('/panel', function () {
            abort_unless(Auth::user() && in_array(Auth::user()->rol, ['tripulacion', 'admin'], true), 403);

            return view('internal.panel', ['user' => Auth::user()]);
        })->name('personal.panel');
    });
});

Route::get('/panel', function () {
    return view('internal.panel', [
        'user' => Auth::user(),
    ]);
})->middleware('auth')->name('panel.index');

Route::get('/vuelos', [VueloController::class, 'index'])->name('vuelos.index');

Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');

Route::get('/equipaje', function () {
    return view('client.equipaje');
})->name('equipaje.index');

