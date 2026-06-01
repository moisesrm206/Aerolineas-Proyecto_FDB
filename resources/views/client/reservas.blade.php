@extends('templates.app')

@section('title', 'Mis Reservas | AeroControl')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        @section('hero')
            @include('shared.page-hero', [
                'label' => 'Gestión de reservas',
                'title' => 'Mis Reservas',
                'subtitle' => 'Revisa tus reservas, pagos y check-ins desde una sola vista.',
            ])
        @endsection
        @if (session('status'))
            <div class="glass-panel rounded-3xl border border-emerald-300/20 bg-emerald-300/10 px-4 py-3 text-sm text-emerald-100 sm:px-5">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->has('general'))
            <div class="glass-panel rounded-3xl border border-rose-300/20 bg-rose-300/10 px-4 py-3 text-sm text-rose-100 sm:px-5">
                {{ $errors->first('general') }}
            </div>
        @endif

        <div class="space-y-3 sm:space-y-4">
            <div>
                @include('shared.page-title', [
                    'label' => 'Gestión de reservas',
                    'title' => 'Mis Reservas',
                ])
            </div>

            <div style="display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:.5rem; width:100%; text-align:center;" class="sm:gap-4">
                <div class="glass-panel min-w-0 rounded-3xl px-3 py-3 sm:rounded-3xl sm:px-5 sm:py-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-white/45">Total</p>
                    <p class="mt-1 text-lg font-semibold text-white sm:text-2xl">{{ $resumen['total'] ?? 0 }}</p>
                </div>

                <div class="glass-panel min-w-0 rounded-3xl px-3 py-3 sm:rounded-3xl sm:px-5 sm:py-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-white/45">Pagadas</p>
                    <p class="mt-1 text-lg font-semibold text-white sm:text-2xl">{{ $resumen['pagadas'] ?? 0 }}</p>
                </div>

                <div class="glass-panel min-w-0 rounded-3xl px-3 py-3 sm:rounded-3xl sm:px-5 sm:py-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-white/45">Check-in</p>
                    <p class="mt-1 text-lg font-semibold text-white sm:text-2xl">{{ $resumen['checkin'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-4 sm:space-y-5">
            @forelse ($reservas as $reserva)
                <article class="glass-panel rounded-4xl p-4 transition duration-300 hover:-translate-y-0.5 hover:border-white/20 sm:p-6">
                    <div class="grid gap-5 xl:grid-cols-[1fr_auto] xl:items-center">
                        <div class="grid gap-5 md:grid-cols-[auto_1fr] md:items-start">
                            <div class="flex h-14 w-14 items-center justify-center rounded-full border border-white/10 bg-white/5 text-2xl text-[#e8d6bb]">
                                <ion-icon name="ticket-sharp"></ion-icon>
                            </div>

                            <div class="space-y-3 sm:space-y-4">
                                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                    <div>
                                        <h2 class="text-xl font-semibold text-white sm:text-2xl">Vuelo {{ $reserva->codigo_vuelo }}</h2>
                                        <p class="text-sm text-white/60">{{ $reserva->reserva_label }}</p>
                                    </div>
                                    <span class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-medium ring-1 {{ $reserva->badge_class }}">
                                        {{ $reserva->badge_text }}
                                    </span>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3 sm:gap-4">
                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-3 sm:px-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-white/45">Ruta</p>
                                        <p class="mt-1 text-base font-medium text-white">{{ $reserva->ruta_resumen }}</p>
                                        <p class="mt-1 text-sm text-white/55">{{ $reserva->origen_ciudad }} → {{ $reserva->destino_ciudad }}</p>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-3 sm:px-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-white/45">Fecha</p>
                                        <p class="mt-1 text-base font-medium text-white">{{ $reserva->fecha_formateada }}</p>
                                        <p class="mt-1 text-sm text-white/55">Salida {{ $reserva->hora_salida }}</p>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-3 sm:px-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-white/45">Asiento</p>
                                        <p class="mt-1 text-base font-medium text-white">{{ $reserva->asiento }}</p>
                                        <p class="mt-1 text-sm text-white/55">{{ $reserva->clase }} · {{ $reserva->pago_estado }}</p>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3 sm:gap-4">
                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-3 sm:px-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-white/45">Aeronave</p>
                                        <p class="mt-1 text-base font-medium text-white">{{ $reserva->vuelo?->aeronave?->matricula ?? 'N/D' }}</p>
                                        <p class="mt-1 text-sm text-white/55">{{ $reserva->vuelo?->aeronave?->modelo?->nombre_comercial ?? 'Sin modelo' }}</p>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-3 sm:px-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-white/45">Estado del vuelo</p>
                                        <p class="mt-1 text-base font-medium text-white">{{ ucfirst($reserva->vuelo?->estado ?? 'programado') }}</p>
                                        <p class="mt-1 text-sm text-white/55">{{ $reserva->codigo_vuelo }}</p>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-3 sm:px-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-white/45">Reserva</p>
                                        <p class="mt-1 text-base font-medium text-white">{{ $reserva->fecha_reserva }}</p>
                                        <p class="mt-1 text-sm text-white/55">{{ $reserva->boletos_total }} boleto(s)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-row gap-3 xl:flex-col xl:items-end">
                            <a href="#" class="inline-flex min-w-28 items-center justify-center rounded-2xl bg-cyan-500 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-cyan-400">
                                Ver boleto
                            </a>
                            @auth
                                @if((int) (Auth::user()->pasajero?->id_pasajero ?? 0) === (int) $reserva->id_pasajero && !str_contains(mb_strtolower((string) ($reserva->estado ?? '')), 'check'))
                                    <form action="{{ route('reservas.checkin', $reserva->id_reserva) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex min-w-28 items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-medium text-white/80 transition hover:bg-white/10">
                                            Check-in
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex min-w-28 items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-medium text-white/50">
                                        Check-in hecho
                                    </span>
                                @endif
                            @endauth
                        </div>
                    </div>
                </article>
            @empty
                <article class="glass-panel rounded-4xl p-8 text-center">
                    <h2 class="text-2xl font-semibold text-white">Todavía no hay reservas cargadas</h2>
                    <p class="mt-3 text-white/60">Cuando existan registros en la tabla reserva, aparecerán aquí con sus vuelos, asientos y pagos asociados.</p>
                </article>
            @endforelse
            <div class="mt-6">
                {{ $reservas->links() }}
            </div>
        </div>
    </div>
@endsection