@extends('templates.app')

@section('title', 'Vuelos disponibles | AeroControl')

@section('content')
    <section class="space-y-8">
        @section('hero')
            @include('shared.page-hero', [
                'label' => 'Operación activa',
                'title' => 'Vuelos disponibles para reservar',
                'subtitle' => 'Revisa salidas, destinos y disponibilidad en una sola pantalla.',
            ])
        @endsection
        <div class="grid gap-6 lg:grid-cols-2">
            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/55">Programados</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $resumen['programados'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-cyan-300">Listos para salir</p>
            </article>

            @if(!empty($modo_admin))
                <article class="glass-panel rounded-3xl p-5">
                    <p class="text-sm text-white/55">En vuelo</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $resumen['en_vuelo'] ?? 0 }}</p>
                    <p class="mt-2 text-sm text-amber-300">Seguimiento operativo</p>
                </article>
            @endif
        </div>

        <div class="grid gap-4 lg:grid-cols-[1fr_0.35fr]">
            <div class="glass-panel rounded-4xl p-5 sm:p-6">
                <form action="{{ route('vuelos.lista') }}" method="GET" class="grid gap-4 lg:grid-cols-[1fr_1fr_1fr_auto]">
                    @include('shared.typeahead-search', [
                        'name' => 'origen',
                        'searchId' => 'origen',
                        'resultsId' => 'origen-results',
                        'label' => 'Origen',
                        'value' => $filtros['origen'] ?? '',
                        'placeholder' => 'Ej: MEX',
                        'icon' => 'location-sharp',
                        'showResults' => true,
                    ])

                    @include('shared.typeahead-search', [
                        'name' => 'destino',
                        'searchId' => 'destino',
                        'resultsId' => 'destino-results',
                        'label' => 'Destino',
                        'value' => $filtros['destino'] ?? '',
                        'placeholder' => 'Ej: LAX',
                        'icon' => 'airplane-sharp',
                        'showResults' => true,
                    ])

                    @include('shared.typeahead-search', [
                        'name' => 'fecha',
                        'searchId' => 'fecha',
                        'label' => 'Fecha',
                        'value' => $filtros['fecha'] ?? '',
                        'placeholder' => 'dd/mm/aaaa',
                        'icon' => 'time-sharp',
                        'showResults' => false,
                    ])

                <button type="submit" class="primary-button inline-flex items-center justify-center gap-3 rounded-2xl px-8 py-3.5 text-sm font-semibold text-white transition duration-300 lg:self-end">
                    <ion-icon name="search-sharp" class="text-lg leading-none"></ion-icon>
                    Buscar
                </button>
                </form>
            </div>

            <div class="glass-panel rounded-4xl p-5 sm:p-6">
                <p class="section-label">Filtros rápidos</p>
                <h2 class="mt-2 text-lg font-semibold text-white">Optimiza la búsqueda</h2>
                <div class="mt-4 flex flex-wrap gap-3 text-sm">
                    <a href="{{ route('vuelos.lista', array_merge(request()->query(), ['fecha' => date('d/m/Y', strtotime('+1 day'))])) }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-white/75">Salida mañana</a>
                    <a href="{{ route('vuelos.lista', array_merge(request()->query(), ['direct' => 1])) }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-white/75">Solo directos</a>
                    <a href="{{ route('vuelos.lista', array_merge(request()->query(), ['cheap' => 1])) }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-white/75">Más baratos</a>
                    <a href="{{ route('vuelos.lista', array_merge(request()->query(), ['seats' => 1])) }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-white/75">Más asientos</a>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
            <div class="space-y-6">
                @forelse ($vuelos ?? [] as $vuelo)
                    <article class="glass-panel rounded-4xl p-6 transition duration-300 hover:-translate-y-0.5 hover:border-white/20">
                        <div class="grid gap-6 lg:grid-cols-[auto_1fr_auto] lg:items-center">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-2xl text-[#e8d6bb]"><ion-icon name="airplane-sharp"></ion-icon></div>

                            <div class="space-y-5">
                                <div class="flex flex-wrap items-center gap-3">
                                    <div>
                                        <p class="text-sm text-white/55">Vuelo {{ $vuelo->id_vuelo ?? 'N/D' }}</p>
                                        <p class="mt-1 text-base font-semibold text-white">{{ $vuelo->estado ?? 'programado' }}</p>
                                    </div>
                                    <span class="rounded-full border border-cyan-300/20 bg-cyan-300/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-cyan-200">Activo</span>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-[1fr_auto_1fr] sm:items-center">
                                    <div>
                                        <p class="text-3xl font-semibold text-white">{{ $vuelo->origen ?? '---' }}</p>
                                        <p class="mt-2 text-sm text-white/65">{{ $vuelo->origen_ciudad ?? '' }}</p>
                                        <p class="text-sm text-white/45">{{ $vuelo->salida ?? '--:--' }} · {{ $vuelo->fecha ?? '' }}</p>
                                    </div>

                                    <div class="hidden items-center gap-3 sm:flex">
                                        <span class="h-px w-24 bg-white/20"></span>
                                        <span class="text-white/35"><ion-icon name="airplane-sharp"></ion-icon></span>
                                        <span class="h-px w-24 bg-white/20"></span>
                                    </div>

                                    <div class="sm:text-right">
                                        <p class="text-3xl font-semibold text-white">{{ $vuelo->destino ?? '---' }}</p>
                                        <p class="mt-2 text-sm text-white/65">{{ $vuelo->destino_ciudad ?? '' }}</p>
                                        <p class="text-sm text-white/45">{{ $vuelo->llegada ?? '--:--' }} · {{ $vuelo->fecha ?? '' }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-4 text-sm text-white/70">
                                    <span>Aeronave: {{ $vuelo->aeronave_matricula ?? 'N/D' }}</span>
                                    <span>Capacidad: {{ $vuelo->capacidad_max ?? 'N/D' }}</span>
                                    <span>Distancia: {{ $vuelo->distancia_km ?? 'N/D' }} km</span>
                                </div>
                            </div>

                            <div class="flex flex-col items-start gap-4 border-t border-white/10 pt-5 lg:border-0 lg:pt-0 lg:text-right">
                                <div>
                                    <p class="text-3xl font-bold text-white">{{ $vuelo->estado ?? 'programado' }}</p>
                                    <p class="mt-1 text-sm text-white/55">Estado</p>
                                </div>
                                @auth
                                    <form action="{{ route('reservas.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_vuelo" value="{{ $vuelo->id_vuelo }}" />
                                        <button type="submit" class="primary-button rounded-2xl px-6 py-3 text-sm font-semibold text-white transition duration-300">Reservar</button>
                                    </form>
                                @else
                                    <a href="{{ route('iniciar.sesion') }}" class="primary-button rounded-2xl px-6 py-3 text-sm font-semibold text-white transition duration-300">Iniciar sesión para reservar</a>
                                @endauth
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="glass-panel rounded-4xl p-8 text-center">
                        <h2 class="text-2xl font-bold text-white">No hay vuelos cargados todavía</h2>
                        <p class="mt-3 text-white/65">
                            Cuando la tabla vuelo tenga registros, aparecerán aquí automáticamente.
                        </p>
                    </div>
                @endforelse
                <div class="mt-6">
                    {{ $vuelos->links() }}
                </div>
            </div>

            <aside class="space-y-6">
                
                <section class="glass-panel rounded-4xl p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-300">Ruta destacada</p>
                    <h3 class="mt-3 text-2xl font-bold text-white">{{ $vueloDestacado->ruta ?? 'Sin vuelos' }}</h3>
                    <p class="mt-3 text-sm leading-7 text-white/70">
                        {{ $vueloDestacado ? 'Salida ' . $vueloDestacado->salida . ' y llegada ' . $vueloDestacado->llegada  : 'Cuando cargues vuelos, aquí se mostrará la ruta más reciente.' }}
                    </p>
                    <div class="mt-6 grid gap-3 text-sm text-white/75">
                        <p>Salida: {{ $vueloDestacado->salida ?? '--:--' }}</p>
                        <p>Llegada: {{ $vueloDestacado->llegada ?? '--:--' }}</p>
                        <p>Aeronave: {{ $vueloDestacado->aeronave_matricula ?? 'N/D' }}</p>
                    </div>
                </section>
            </aside>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (!window.initTypeaheadPicker) {
                    return;
                }

                const aeropuertos = @json($aeropuertosBusqueda ?? []);

                const buildTypeahead = (inputSelector, resultsSelector) => window.initTypeaheadPicker({
                    inputSelector,
                    resultsSelector,
                    items: aeropuertos,
                    minChars: 1,
                    filterItems(list, query) {
                        return list.filter((item) => {
                            const texto = `${item.codigo_iata || ''} ${item.nombre || ''} ${item.ciudad || ''}`.toLowerCase();
                            return texto.includes(query);
                        });
                    },
                    renderItem(item, index, activeIndex) {
                        const isActive = index === activeIndex;
                        const activeClass = isActive ? 'bg-cyan-300/15 ring-1 ring-cyan-300/40' : 'hover:bg-white/5';

                        return `<div class="p-2 rounded cursor-pointer transition ${activeClass}" data-index="${index}" role="option" aria-selected="${isActive ? 'true' : 'false'}">
                            <div class="text-sm ${isActive ? 'text-cyan-100' : ''}">${item.codigo_iata || 'N/D'} · ${item.nombre || 'Sin nombre'}</div>
                            <div class="text-xs text-white/55">${item.ciudad || 'Sin ciudad'}</div>
                        </div>`;
                    },
                    onSelect(item, api) {
                        api.input.value = item.codigo_iata || '';
                        api.hide();
                    },
                });

                buildTypeahead('#origen', '#origen-results');
                buildTypeahead('#destino', '#destino-results');
            });
        </script>
    @endpush
@endsection