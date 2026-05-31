<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OperacionTripulacionController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\VueloController;
use App\Models\User;
use App\Models\Pasajero;
use App\Models\Tripulacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::get('/', [App\Http\Controllers\PageController::class, 'home'])->name('inicio');

Route::get('/iniciar_sesion', [LoginController::class, 'showLogin'])->name('iniciar.sesion');

Route::get('/registro', [RegisterController::class, 'showRegister'])->name('registro');

Route::post('/iniciar_sesion', [LoginController::class, 'login'])->name('iniciar.sesion.guardar');

Route::post('/registro', [RegisterController::class, 'register'])->name('registro.guardar');

Route::middleware('auth')->group(function () {
    Route::patch('/cuenta', [ProfileController::class, 'update'])->name('cuenta.actualizar');
    Route::delete('/cuenta', [ProfileController::class, 'destroy'])->name('cuenta.eliminar');
    Route::post('/cerrar_sesion', [LoginController::class, 'logout'])->name('cerrar.sesion');

    Route::get('/cuenta', function () {
        return view('internal.cuenta', ['user' => Auth::user()]);
    })->name('cuenta.editar');

    Route::prefix('admin')->group(function () {
        Route::get('/panel', [App\Http\Controllers\AdminController::class, 'panel'])->name('admin.panel');

        Route::get('/aeronaves', [App\Http\Controllers\AdminController::class, 'aeronaves'])->name('admin.aeronaves');
        Route::get('/aeronaves/nueva', [App\Http\Controllers\AdminController::class, 'createAeronave'])->name('admin.aeronaves.crear');
        Route::post('/aeronaves', [App\Http\Controllers\AdminController::class, 'storeAeronave'])->name('admin.aeronaves.store');
        Route::get('/aeronaves/{aeronave}/editar', [App\Http\Controllers\AdminController::class, 'editAeronave'])->name('admin.aeronaves.editar');
        Route::put('/aeronaves/{aeronave}', [App\Http\Controllers\AdminController::class, 'updateAeronave'])->name('admin.aeronaves.actualizar');
        Route::post('/aeronaves/{aeronave}/mantenimiento', [App\Http\Controllers\AdminController::class, 'marcarAeronaveMantenimiento'])->name('admin.aeronaves.mantenimiento');

        Route::get('/cuenta/nueva', [App\Http\Controllers\AdminController::class, 'createAccount'])->name('admin.cuentas.nueva');

        Route::post('/cuenta', [App\Http\Controllers\AdminController::class, 'storeAccount'])->name('admin.cuentas.guardar');

        // Vuelos (admin)
        Route::get('/vuelos', [App\Http\Controllers\AdminController::class, 'vuelos'])->name('admin.vuelos');
        Route::get('/vuelos/nuevo', [App\Http\Controllers\AdminController::class, 'crearVuelo'])->name('admin.vuelos.crear');
        Route::post('/vuelos', [App\Http\Controllers\AdminController::class, 'storeVuelo'])->name('admin.vuelos.store');
        Route::get('/vuelos/{vuelo}/editar', [App\Http\Controllers\AdminController::class, 'editVuelo'])->name('admin.vuelos.editar');
        Route::put('/vuelos/{vuelo}', [App\Http\Controllers\AdminController::class, 'updateVuelo'])->name('admin.vuelos.actualizar');
        Route::get('/vuelos/{vuelo}/tripulacion', [App\Http\Controllers\AdminController::class, 'asignarForm'])->name('admin.vuelos.asignar.form');
        Route::post('/vuelos/{vuelo}/tripulacion', [App\Http\Controllers\AdminController::class, 'asignarTripulacion'])->name('admin.vuelos.asignar');
    });

    Route::prefix('personal')->group(function () {
        Route::get('/panel', [App\Http\Controllers\PanelController::class, 'personal'])->name('personal.panel');
    });

    // Reservas: crear reserva (usuario autenticado)
    Route::post('/reservas', [App\Http\Controllers\ReservaController::class, 'store'])->name('reservas.store');
});

Route::get('/panel', [App\Http\Controllers\PanelController::class, 'show'])->middleware('auth')->name('panel.principal');

Route::get('/operacion', [OperacionTripulacionController::class, 'index'])
    ->middleware('auth')
    ->name('operacion.tripulacion');

Route::get('/vuelos', [VueloController::class, 'index'])->name('vuelos.lista');

Route::get('/mis_vuelos', [VueloController::class, 'misVuelos'])->middleware('auth')->name('vuelos.mis');

Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.lista');

Route::get('/equipaje', [App\Http\Controllers\PageController::class, 'equipaje'])->name('equipaje.lista');

