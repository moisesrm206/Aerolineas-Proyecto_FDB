@extends('templates.app')

@section('title', 'Gestión de Vuelos | Admin')

@section('content')
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="section-label">Administración</p>
                <h1 class="mt-2 text-3xl font-bold text-white">Gestión de vuelos</h1>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.vuelos.crear') }}" class="primary-button p-4 border-r">Crear vuelo</a>
                <a href="{{ route('admin.panel') }}" class="outline-button">Volver al panel</a>
            </div>
        </div>

        <div class="glass-panel p-5">
            <form action="{{ route('admin.vuelos') }}" method="GET" class="grid gap-4 sm:grid-cols-3">
                <input name="origen" placeholder="Origen (IATA)" value="{{ $filtros['origen'] ?? '' }}" class="rounded" />
                <input name="destino" placeholder="Destino (IATA)" value="{{ $filtros['destino'] ?? '' }}" class="rounded" />
                <input name="fecha" placeholder="dd/mm/YYYY" value="{{ $filtros['fecha'] ?? '' }}" class="rounded" />
                <button class="primary-button">Filtrar</button>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($vuelos as $vuelo)
                <div class="glass-panel p-4 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-white/60">Vuelo {{ $vuelo->id_vuelo }}</div>
                        <div class="text-lg font-semibold text-white">{{ $vuelo->ruta_txt ?? 'Sin ruta' }} — {{ $vuelo->salida_txt ?? '' }}</div>
                        <div class="text-sm text-white/60">Aeronave: {{ $vuelo->aeronave?->matricula ?? 'N/D' }} · Estado: {{ $vuelo->estado }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.vuelos.editar', $vuelo->id_vuelo) }}" class="outline-button">Editar vuelo</a>
                        <a href="{{ route('admin.vuelos.asignar.form', $vuelo->id_vuelo) }}" class="outline-button">Asignar tripulación</a>
                    </div>
                </div>
            @empty
                <div class="glass-panel p-6 text-center">
                    <p class="text-white">No hay vuelos cargados.</p>
                </div>
            @endforelse

            <div>{{ $vuelos->links() }}</div>
        </div>
    </section>
@endsection
