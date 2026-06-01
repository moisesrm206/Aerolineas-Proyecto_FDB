@extends('templates.app')

@section('title', 'Gestión de Tripulación | AeroControl')

@section('content')
    <section class="space-y-8">
        @section('hero')
            @include('shared.page-hero', [
                'label' => 'Operación activa',
                'title' => 'Gestión de Tripulación',
                'subtitle' => 'Seguimiento de vuelos próximos y pasados por rol operativo.',
            ])
        @endsection
        <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr] lg:items-end">
            <div>
                @include('shared.page-title', [
                    'label' => 'Operación activa',
                    'title' => 'Gestión de Tripulación',
                    'subtitle' => 'Seguimiento de vuelos próximos y pasados por rol operativo.',
                ])
                <p class="mt-2 text-xs uppercase tracking-[0.26em] text-white/45">Actualizado {{ $nowLabel }}</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="glass-panel rounded-3xl p-5">
                    <p class="text-sm text-white/60">Próximos vuelos</p>
                    <p class="mt-2 text-3xl font-semibold text-white">{{ $summary['upcoming'] ?? 0 }}</p>
                </article>
                <article class="glass-panel rounded-3xl p-5">
                    <p class="text-sm text-white/60">Vuelos pasados</p>
                    <p class="mt-2 text-3xl font-semibold text-white">{{ $summary['past'] ?? 0 }}</p>
                </article>
                <article class="glass-panel rounded-3xl p-5">
                    <p class="text-sm text-white/60">Asignados</p>
                    <p class="mt-2 text-3xl font-semibold text-white">{{ $summary['assigned'] ?? 0 }}</p>
                </article>
                <article class="glass-panel rounded-3xl p-5">
                    <p class="text-sm text-white/60">Rol</p>
                    <p class="mt-2 text-3xl font-semibold text-white">{{ ucfirst($summary['role'] ?? 'tripulacion') }}</p>
                </article>
            </div>
        </div>

        <section class="mt-8 grid gap-6 xl:grid-cols-2">
            <div class="glass-panel rounded-[1.75rem] p-6">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#e8d6bb]">Próximos vuelos</p>
                        <h3 class="mt-2 text-2xl font-bold text-white">Pendientes de operación</h3>
                    </div>
                    <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-white/80">{{ $upcomingFlights->count() }} vuelos</span>
                </div>

                <div class="space-y-4">
                    @forelse ($upcomingFlights as $vuelo)
                        <article class="glass-panel rounded-2xl p-4 transition hover:-translate-y-0.5">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-lg font-semibold text-white">{{ $vuelo->ruta }}</h4>
                                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-[#e8d6bb]">Próximo</span>
                                    </div>
                                    <p class="mt-1 text-sm text-white/55">Vuelo #{{ $vuelo->id_vuelo }} · {{ $vuelo->salida_formateada }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-white/80">{{ $vuelo->hora_salida }} - {{ $vuelo->hora_llegada }}</p>
                                    <p class="text-xs text-white/45">Aeronave {{ $vuelo->matricula }}</p>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-white/40">Origen</p>
                                    <p class="mt-1 font-medium text-white/80">{{ $vuelo->origen }} · {{ $vuelo->origen_ciudad }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-white/40">Destino</p>
                                    <p class="mt-1 font-medium text-white/80">{{ $vuelo->destino }} · {{ $vuelo->destino_ciudad }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-white/40">Tripulación asignada</p>
                                    @if(!empty($vuelo->tripulacion_asignaciones) && count($vuelo->tripulacion_asignaciones) > 0)
                                        <ul class="mt-1 space-y-1 text-sm text-white/80">
                                            @foreach($vuelo->tripulacion_asignaciones as $asignacion)
                                                <li>{{ $asignacion->nombre }} <span class="text-white/45">({{ $asignacion->rol }})</span></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-1 font-medium text-white/80">Sin asignar</p>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-white/15 bg-white/5 p-6 text-center text-white/55">
                            No hay vuelos próximos para mostrar.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="glass-panel rounded-[1.75rem] p-6">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#e8d6bb]">Vuelos pasados</p>
                        <h3 class="mt-2 text-2xl font-bold text-white">Historial reciente</h3>
                    </div>
                    <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-white/80">{{ $pastFlights->count() }} vuelos</span>
                </div>

                <div class="space-y-4">
                    @forelse ($pastFlights as $vuelo)
                        <article class="glass-panel rounded-2xl p-4 transition hover:-translate-y-0.5">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-lg font-semibold text-white">{{ $vuelo->ruta }}</h4>
                                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-[#e8d6bb]">Pasado</span>
                                    </div>
                                    <p class="mt-1 text-sm text-white/55">Vuelo #{{ $vuelo->id_vuelo }} · {{ $vuelo->salida_formateada }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-white/80">{{ $vuelo->hora_salida }} - {{ $vuelo->hora_llegada }}</p>
                                    <p class="text-xs text-white/45">Aeronave {{ $vuelo->matricula }}</p>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-white/40">Origen</p>
                                    <p class="mt-1 font-medium text-white/80">{{ $vuelo->origen }} · {{ $vuelo->origen_ciudad }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-white/40">Destino</p>
                                    <p class="mt-1 font-medium text-white/80">{{ $vuelo->destino }} · {{ $vuelo->destino_ciudad }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-white/40">Tripulación asignada</p>
                                    @if(!empty($vuelo->tripulacion_asignaciones) && count($vuelo->tripulacion_asignaciones) > 0)
                                        <ul class="mt-1 space-y-1 text-sm text-white/80">
                                            @foreach($vuelo->tripulacion_asignaciones as $asignacion)
                                                <li>{{ $asignacion->nombre }} <span class="text-white/45">({{ $asignacion->rol }})</span></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-1 font-medium text-white/80">Sin asignar</p>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-white/15 bg-white/5 p-6 text-center text-white/55">
                            No hay vuelos pasados para mostrar.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </section>
@endsection