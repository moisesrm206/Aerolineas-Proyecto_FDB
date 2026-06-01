@extends('templates.app')

@section('title', 'Gestión de Equipaje | AeroControl')

@section('content')
    <section class="space-y-8">
        @section('hero')
            @include('shared.page-hero', [
                'label' => 'Equipaje',
                'title' => 'Seguimiento de equipaje',
                'subtitle' => 'Consulta tus piezas registradas, su etiqueta y estado actual.',
            ])
        @endsection

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/55">Piezas totales</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $resumen['total'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-cyan-300">Registradas en tu cuenta</p>
            </article>

            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/55">Equipaje de mano</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $resumen['mano'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-emerald-300">Cabina</p>
            </article>

            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/55">Equipaje de bodega</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $resumen['bodega'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-amber-300">Despachado</p>
            </article>

            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/55">En tránsito</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $resumen['en_transito'] ?? 0 }}</p>
                <p class="mt-2 text-sm text-violet-300">Seguimiento operativo</p>
            </article>
        </div>

        <section class="glass-panel rounded-4xl p-6 sm:p-8">
            <form action="{{ route('equipaje.lista') }}" method="GET" class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
                <div>
                    <p class="section-label">Filtro de vuelos</p>
                    <h3 class="mt-3 text-2xl font-semibold text-white">Mostrar equipaje por periodo</h3>
                    <p class="mt-2 text-sm text-white/65">Puedes ver el equipaje de vuelos actuales, anteriores o todo el historial.</p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <select name="filtro_vuelos" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                        <option value="actuales" @selected(($filtroVuelos ?? 'actuales') === 'actuales')>Vuelos actuales</option>
                        <option value="anteriores" @selected(($filtroVuelos ?? 'actuales') === 'anteriores')>Vuelos anteriores</option>
                        <option value="todos" @selected(($filtroVuelos ?? 'actuales') === 'todos')>Todo el historial</option>
                    </select>

                    <button type="submit" class="primary-button inline-flex items-center justify-center rounded-2xl px-6 py-3 text-sm font-semibold text-white transition duration-300">
                        Aplicar filtro
                    </button>
                </div>
            </form>
        </section>

        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <section class="glass-panel rounded-4xl p-6 sm:p-8">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="section-label">Mi equipaje</p>
                        <h2 class="mt-4 text-3xl font-bold text-white">{{ $equipajes->count() ? 'Tus piezas registradas' : 'Aún no tienes equipaje registrado' }}</h2>
                        <p class="mt-3 max-w-3xl text-white/65">
                            Cada pieza se identifica con una etiqueta y cambia de estado a medida que avanza en el proceso de embarque.
                        </p>
                    </div>
                </div>

                <div class="mt-8 space-y-4">
                    @forelse($equipajes as $equipaje)
                        @php
                            $estadoClass = match($equipaje->estado) {
                                'registrado' => 'border-cyan-300/20 bg-cyan-300/10 text-cyan-100',
                                'en_cinta' => 'border-amber-300/20 bg-amber-300/10 text-amber-100',
                                'cargado' => 'border-emerald-300/20 bg-emerald-300/10 text-emerald-100',
                                'en_transito' => 'border-violet-300/20 bg-violet-300/10 text-violet-100',
                                'entregado' => 'border-sky-300/20 bg-sky-300/10 text-sky-100',
                                'perdido' => 'border-rose-300/20 bg-rose-300/10 text-rose-100',
                                default => 'border-white/10 bg-white/5 text-white/80',
                            };

                            $tipoLabel = $equipaje->tipo_equipaje === 'mano' ? 'Mano' : 'Bodega';
                        @endphp

                        <article class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-xl font-semibold text-white">{{ $equipaje->etiqueta }}</h3>
                                        <span class="rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $estadoClass }}">
                                            {{ str_replace('_', ' ', $equipaje->estado) }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-white/60">Tipo: {{ $tipoLabel }} · Peso: {{ number_format((float) $equipaje->peso_kg, 2) }} kg</p>
                                </div>

                                <div class="text-right text-sm text-white/65">
                                    <p class="font-semibold text-white">Boleto #{{ $equipaje->id_boleto ?? 'N/D' }}</p>
                                    <p>Asiento {{ $equipaje->boleto?->numero_asiento ?? 'Sin asiento' }}</p>
                                    <p>Clase {{ $equipaje->boleto?->clase ?? 'Sin clase' }}</p>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-3 text-sm text-white/70">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-white/40">Vuelo</p>
                                    <p class="mt-1 font-medium text-white">{{ $equipaje->boleto?->reserva?->vuelo?->ruta?->aeropuertoOrigen?->codigo_iata ?? 'Sin vuelo asociado' }} → {{ $equipaje->boleto?->reserva?->vuelo?->ruta?->aeropuertoDestino?->codigo_iata ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-white/40">Reserva</p>
                                    <p class="mt-1 font-medium text-white">#{{ $equipaje->boleto?->reserva?->id_reserva ?? 'Sin reserva' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-white/40">Pasajero</p>
                                    <p class="mt-1 font-medium text-white">{{ $pasajero?->usuario?->nombre ?? 'Sin pasajero' }}</p>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-3xl border border-dashed border-white/15 bg-white/5 p-6 text-center text-white/65">
                            No tienes equipaje registrado todavía.
                        </div>
                    @endforelse
                </div>
            </section>

            <aside class="space-y-6">
                <section class="glass-panel rounded-4xl p-6 sm:p-8">                    
                    <h3 class="mt-3 text-2xl font-semibold text-white">Flujo de seguimiento</h3>
                    <ul class="mt-5 space-y-3 text-sm text-white/70">
                        <li>• Registrado: el equipaje ya fue ingresado al sistema.</li>
                        <li>• En cinta: está en el proceso de revisión o envío.</li>
                        <li>• Cargado: ya fue asignado al vuelo.</li>
                        <li>• En tránsito: viaja con el vuelo.</li>
                        <li>• Entregado: llegó a destino.</li>
                        <li>• Perdido: requiere atención manual.</li>
                    </ul>
                </section>                
            </aside>
        </div>

        <section class="rounded-4xl border border-amber-300/40 bg-[linear-gradient(180deg,rgba(250,204,21,0.18),rgba(255,255,255,0.02))] p-6 sm:p-8">
            <h3 class="text-2xl font-semibold text-white">Artículos prohibidos</h3>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <p class="text-sm font-semibold text-white/80">Equipaje de mano</p>
                    <ul class="mt-3 space-y-2 text-sm text-white/75">
                        <li>• Líquidos no autorizados</li>
                        <li>• Objetos punzocortantes</li>
                        <li>• Baterías sueltas no permitidas</li>
                    </ul>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white/80">Equipaje de bodega</p>
                    <ul class="mt-3 space-y-2 text-sm text-white/75">
                        <li>• Materiales inflamables</li>
                        <li>• Sustancias tóxicas</li>
                        <li>• Dispositivos explosivos</li>
                    </ul>
                </div>
            </div>
        </section>
    </section>
@endsection