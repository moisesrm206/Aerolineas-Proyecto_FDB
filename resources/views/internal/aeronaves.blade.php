@extends('templates.app')

@section('title', 'Gestión de Aeronaves | AeroControl')

@section('content')
    <section class="space-y-8">
        <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr] lg:items-end">
            <div>
                <p class="section-label">Acceso administrativo</p>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl">Gestión de Aeronaves</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-white/70 sm:text-lg">
                    Vista de control para revisar matrícula, modelo, capacidad y mantenimiento a partir de los datos disponibles en Aeronave y ModeloAeronave.
                </p>
                <p class="mt-2 text-xs uppercase tracking-[0.26em] text-white/45">Actualizado {{ $actualizado ?? '' }}</p>
            </div>

            <div class="flex flex-wrap gap-3 lg:justify-end">
                <a href="{{ route('admin.panel') }}" class="outline-button inline-flex items-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold transition duration-300">
                    <ion-icon name="arrow-back-sharp"></ion-icon>
                    Volver al panel
                </a>                
            </div>
        </div>

        @if(session('status'))
            <div class="rounded-3xl border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-medium text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/60">Total aeronaves</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $resumen['total'] ?? 0 }}</p>                
            </article>
            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/60">Modelos distintos</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $resumen['modelos'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-emerald-300">Relacionados con ModeloAeronave</p>
            </article>
            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/60">Operativas</p>
                <p class="mt-2 text-3xl font-semibold text-emerald-300">{{ $resumen['operativas'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-emerald-300">Aeronaves listas para operar</p>
            </article>
            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/60">En mantenimiento</p>
                <p class="mt-2 text-3xl font-semibold text-amber-300">{{ $resumen['en_mantenimiento'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-amber-300">Aeronaves fuera de servicio</p>
            </article>
            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/60">Capacidad total</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $resumen['capacidad_total'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-amber-300">Asientos máximos sumados</p>
            </article>
            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/60">Autonomía promedio</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $resumen['autonomia_promedio'] ?? 0 }} km</p>
                <p class="mt-2 text-sm text-violet-300">Promedio del modelo asociado</p>
            </article>
        </div>

        <section id="flota" class="space-y-5">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="section-label">Flota</p>
                    <h2 class="mt-3 text-3xl font-bold text-white">Aeronaves registradas</h2>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-white/80">{{ $resumen['mantenimientos'] ?? 0 }} mantenimientos vinculados</span>
                    <a href="{{ route('admin.aeronaves.crear') }}" class="primary-button inline-flex items-center rounded-2xl px-5 py-3 text-sm font-semibold text-white transition duration-300">
                        Añadir aeronave
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                @forelse ($aeronaves as $aeronave)
                    <article class="glass-panel rounded-3xl p-6 transition duration-300 hover:-translate-y-0.5 hover:border-white/20">
                        <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-start">
                            <div class="space-y-6">
                                <div class="flex flex-wrap items-start gap-4">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-cyan-400/15 text-2xl text-cyan-200">
                                        <ion-icon name="airplane-sharp"></ion-icon>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h3 class="text-2xl font-semibold text-white">{{ $aeronave->nombre_modelo ?? 'Modelo sin nombre' }}</h3>
                                            <span class="rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $aeronave->estado_clase ?? 'border-white/10 bg-white/5 text-white/75' }}">
                                                {{ $aeronave->estado_mantenimiento ?? 'Sin registro' }}
                                            </span>
                                        </div>
                                        <p class="mt-2 text-sm font-semibold {{ $aeronave->estado_operativo_clase ?? 'text-emerald-300' }}">
                                            Estado operativo: {{ $aeronave->estado_operativo ?? 'Operativa' }}
                                        </p>
                                        <p class="mt-1 text-sm text-white/60">Matrícula: {{ $aeronave->matricula ?? 'N/D' }} · Fabricante: {{ $aeronave->fabricante ?? 'Sin dato' }}</p>
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.18em] text-white/40">Capacidad</p>
                                        <p class="mt-1 text-lg font-semibold text-white">{{ $aeronave->capacidad_max ?? 'N/D' }} pasajeros</p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.18em] text-white/40">Autonomía</p>
                                        <p class="mt-1 text-lg font-semibold text-white">{{ $aeronave->autonomia_km ? number_format((float) $aeronave->autonomia_km, 0, ',', '.') . ' km' : 'Sin dato' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.18em] text-white/40">Último mantenimiento</p>
                                        <p class="mt-1 text-lg font-semibold text-white">{{ $aeronave->ultimo_mantenimiento ?? 'Sin registro' }}</p>
                                        <p class="mt-1 text-xs text-white/45">{{ $aeronave->ultimo_tipo_mantenimiento ?? 'Sin dato' }} · {{ $aeronave->ultimo_responsable ?? 'Sin dato' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.18em] text-white/40">Historial</p>
                                        <p class="mt-1 text-lg font-semibold text-white">{{ $aeronave->mantenimientos_count ?? 0 }} registros</p>
                                        <p class="mt-1 text-xs text-white/45">Modelo: {{ $aeronave->nombre_modelo ?? 'N/D' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 lg:items-end">
                                <a href="{{ route('admin.aeronaves.editar', $aeronave->id_aeronave) }}" class="primary-button inline-flex items-center justify-center rounded-2xl px-6 py-3 text-sm font-semibold text-white transition duration-300">
                                    Editar información
                                </a>
                                <form action="{{ route('admin.aeronaves.mantenimiento', $aeronave->id_aeronave) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="outline-button inline-flex w-full items-center justify-center rounded-2xl px-6 py-3 text-sm font-semibold transition duration-300">
                                        Poner en mantenimiento
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="glass-panel rounded-3xl p-8 text-center text-white/65">
                        No hay aeronaves registradas todavía.
                    </div>
                @endforelse

                <div class="pt-4">
                    <div class="flex flex-wrap items-center justify-between gap-4 text-sm text-white/55">
                        <p>
                            Mostrando {{ $aeronaves->firstItem() ?? 0 }} - {{ $aeronaves->lastItem() ?? 0 }} de {{ $aeronaves->total() ?? 0 }} aeronaves
                        </p>
                        <p>Página {{ $aeronaves->currentPage() ?? 1 }} de {{ $aeronaves->lastPage() ?? 1 }}</p>
                    </div>
                    <div class="mt-4">
                        {{ $aeronaves->links() }}
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection