@extends('templates.app')

@section('title', 'Panel | AeroControl')

@section('content')
    <section class="space-y-8">
        <div class="flex-wrap gap-4 lg:items-end bg-blue-800/20 rounded-3xl p-6">
            <h2 class="mt-4 text-4xl font-bold tracking-tight sm:text-5xl">Bienvenido, {{ $user->nombre }}</h2>
            <p class="section-label">Acceso autenticado</p>
        </div>

        @if(($user->rol ?? 'pasajero') === 'admin')
            <div class="grid gap-6 md:grid-cols-3">
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Administración</p>
                    <h3 class="mt-3 text-2xl font-semibold">Usuarios y permisos</h3>
                    <p class="mt-3 text-white/65">Aquí puedes concentrar altas de cuentas, roles y accesos.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('admin.aeronaves') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Aeronaves</a>
                        <a href="{{ route('admin.vuelos') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Vuelos</a>
                        <a href="{{ route('reservas.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Reservas</a>
                        <a href="{{ route('equipaje.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Equipaje</a>
                        <a href="{{ route('operacion.tripulacion') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Operación</a>
                    </div>
                </article>
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Operación</p>
                    <h3 class="mt-3 text-2xl font-semibold">Vuelos y reservas</h3>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('admin.vuelos.crear') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Agregar vuelo</a>
                        <a href="{{ route('admin.vuelos') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Ver vuelos</a>
                        <a href="{{ route('reservas.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Ver reservas</a>
                    </div>
                </article>
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Tripulación</p>
                    <h3 class="mt-3 text-2xl font-semibold">Asignaciones</h3>
                    <p class="mt-3 text-white/65">Más adelante aquí puedes ver asignaciones, turnos y estados de vuelo.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('operacion.tripulacion') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Ver operación</a>
                    </div>
                </article>
            </div>
        @elseif(($user->rol ?? 'pasajero') === 'tripulacion')
            <div class="grid gap-6 md:grid-cols-3">
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Tripulación</p>
                    <h3 class="mt-3 text-2xl font-semibold">Asignaciones del día</h3>
                    <p class="mt-3 text-white/65">Centraliza vuelos asignados, rol y estados operativos sin duplicar vistas.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('operacion.tripulacion') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Mis vuelos</a>
                        <a href="{{ route('reservas.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Reservas</a>
                    </div>
                </article>
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Control</p>
                    <h3 class="mt-3 text-2xl font-semibold">Abordaje y puertas</h3>
                    <p class="mt-3 text-white/65">Aquí pueden vivir listas de abordaje, puertas y observaciones de vuelo.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('vuelos.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Vuelos</a>
                    </div>
                </article>
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Soporte</p>
                    <h3 class="mt-3 text-2xl font-semibold">Incidencias</h3>
                    <p class="mt-3 text-white/65">Puedes sumar reportes rápidos sin mezclarlo con el panel del pasajero.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('reservas.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Reservas</a>
                    </div>
                </article>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2">
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Pasajero</p>
                    <h3 class="mt-3 text-2xl font-semibold">Mis reservas</h3>
                    <p class="mt-3 text-white/65">Enlaza aquí la vista de reservas que ya tienes montada.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('reservas.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Mis reservas</a>
                        <a href="{{ route('vuelos.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Buscar vuelos</a>
                        <a href="{{ route('equipaje.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Equipaje</a>
                    </div>
                </article>
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Perfil</p>
                    <h3 class="mt-3 text-2xl font-semibold">Datos personales</h3>
                    <p class="mt-3 text-white/65">Tu perfil vive en pasajero y se relaciona con la cuenta de users.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('cuenta.editar') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Editar cuenta</a>
                    </div>
                </article>
            </div>
        @endif
    </section>
@endsection