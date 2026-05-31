@extends('templates.app')

@section('title', 'Mis vuelos')

@section('content')
    <section>
        <p class="section-label">Mis vuelos</p>
        <h1 class="mt-2 text-2xl font-bold text-white">Vuelos en los que has volado</h1>

        <div class="mt-6 space-y-4">
            @forelse($vuelos as $vuelo)
                <div class="glass-panel p-4">
                    <div class="text-white">Vuelo {{ $vuelo->id_vuelo }} — {{ $vuelo->ruta }}</div>
                    <div class="text-sm text-white/65">Salida {{ $vuelo->salida }} · {{ $vuelo->fecha }}</div>
                    <div class="text-sm text-white/65">Aeronave: {{ $vuelo->aeronave }}</div>
                </div>
            @empty
                <div class="glass-panel p-4 text-center text-white">No tienes vuelos registrados.</div>
            @endforelse

            <div>{{ $vuelos->links() }}</div>
        </div>
    </section>
@endsection
