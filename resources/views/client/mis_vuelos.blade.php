@extends('templates.app')

@section('title', 'Mis vuelos')

@section('content')
    @section('hero')
        @include('shared.page-hero', [
            'label' => 'Mis viajes',
            'title' => 'Mis vuelos',
            'subtitle' => 'Tus próximas salidas y el historial de viajes.',
        ])
    @endsection
    <section>
        
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
