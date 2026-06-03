@extends('templates.app')

@section('title', 'Panel | AeroControl')

@section('content')
    <section class="space-y-8">
        @section('hero')
            @include('shared.page-hero', [
                'label' => 'Acceso autenticado',
                'title' => 'Bienvenido, ' . ($user->nombre ?? ''),                
            ])
        @endsection

        @if(($user->rol ?? 'pasajero') === 'admin')
            <div class="grid gap-6 md:grid-cols-3">
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Administración</p>
                    <h3 class="mt-3 text-2xl font-semibold">Usuarios y permisos</h3>
                    
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('admin.equipaje.crear') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Registrar equipaje</a>
                        <a href="{{ route('reservas.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Reservas</a>
                        <a href="{{ route('equipaje.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Equipaje</a>
                    </div>
                </article>
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Operación</p>
                    <h3 class="mt-3 text-2xl font-semibold">Vuelos y reservas</h3>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('admin.aeronaves') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Aeronaves</a>
                        <a href="{{ route('admin.vuelos') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Vuelos</a>
                        <a href="{{ route('admin.vuelos.crear') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Agregar vuelo</a>
                        <a href="{{ route('admin.check-in.form') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Check-in mostrador</a>
                    </div>
                </article>
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Tripulación</p>
                    <h3 class="mt-3 text-2xl font-semibold">Asignaciones</h3>                    
                    <div class="mt-6 flex flex-wrap gap-3">                        
                        <a href="{{ route('operacion.tripulacion') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Historial de vuelo</a>
                    </div>
                </article>
            </div>
        @elseif(($user->rol ?? 'pasajero') === 'tripulacion')
            <div class="grid gap-6 md:grid-cols-3">
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Tripulación</p>
                    <h3 class="mt-3 text-2xl font-semibold">Asignaciones del día</h3>
                    
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('operacion.tripulacion') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Mis vuelos</a>
                    </div>
                </article>
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Control</p>
                    <h3 class="mt-3 text-2xl font-semibold">Abordaje y puertas</h3>
                    
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('vuelos.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Vuelos</a>
                    </div>
                </article>
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Soporte</p>
                    <h3 class="mt-3 text-2xl font-semibold">Incidencias</h3>
                    
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
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('reservas.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Mis reservas</a>
                        <a href="{{ route('vuelos.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Buscar vuelos</a>
                        <a href="{{ route('equipaje.lista') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Equipaje</a>
                    </div>
                </article>
                <article class="glass-panel rounded-4xl p-6">
                    <p class="section-label">Perfil</p>
                    <h3 class="mt-3 text-2xl font-semibold">Datos personales</h3>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('cuenta.editar') }}" class="primary-button rounded-2xl px-4 py-2 text-sm font-semibold">Editar cuenta</a>
                    </div>
                </article>
            </div>
        @endif
    </section>
@endsection