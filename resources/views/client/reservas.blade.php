<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas | AeroVuelos</title>
    @php
        $manifestPath = public_path('build/manifest.json');
        $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
        $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
    @endphp
    @if($cssFile)
        <link rel="preload" as="style" href="/build/{{ $cssFile }}">
        <link rel="stylesheet" href="/build/{{ $cssFile }}">
    @endif
    @if($jsFile)
        <link rel="modulepreload" as="script" href="/build/{{ $jsFile }}">
        <script type="module" src="/build/{{ $jsFile }}"></script>
    @endif
</head>
<body class="min-h-screen bg-[#f3f6fb] text-slate-900 antialiased">
    <header class="sticky top-0 z-50 border-b border-blue-700/10 bg-[#1f5cff] shadow-[0_10px_30px_rgba(31,92,255,0.2)]">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="/" class="flex items-center gap-3 text-white">
                <span class="flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-white/10 text-lg font-bold">✈</span>
                <span class="text-xl font-medium tracking-wide">AeroVuelos</span>
            </a>

            <div class="flex items-center gap-3 text-sm sm:gap-5">
                <a href="/login" class="text-white/95 transition hover:text-white">Iniciar Sesión</a>
                <a href="/register" class="rounded-xl bg-white px-5 py-3 font-medium text-[#1f5cff] shadow-sm transition hover:bg-blue-50">Registrarse</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="space-y-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Mis Reservas</h1>
                    <p class="mt-2 text-sm text-slate-500">Las reservas se cargan desde la base de datos y se muestran con el estado actual de cada vuelo.</p>
                </div>

                <div class="grid grid-cols-3 gap-3 text-center sm:min-w-[380px]">
                    <div class="rounded-2xl bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $resumen['total'] ?? 0 }}</p>
                    </div>
                    <div class="rounded-2xl bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Pagadas</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $resumen['pagadas'] ?? 0 }}</p>
                    </div>
                    <div class="rounded-2xl bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Check-in</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $resumen['checkin'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                @forelse ($reservas as $reserva)
                    <article class="rounded-[1.5rem] bg-white p-5 shadow-[0_6px_18px_rgba(15,23,42,0.12)] ring-1 ring-slate-200/70 transition hover:-translate-y-0.5 hover:shadow-[0_14px_35px_rgba(15,23,42,0.16)] sm:p-6">
                        <div class="grid gap-5 xl:grid-cols-[1fr_auto] xl:items-center">
                            <div class="grid gap-5 md:grid-cols-[auto_1fr] md:items-start">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-2xl text-[#1f5cff] shadow-inner">
                                    ✈
                                </div>

                                <div class="space-y-4">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <div>
                                            <h2 class="text-2xl font-semibold text-slate-900">Vuelo {{ $reserva->codigo_vuelo }}</h2>
                                            <p class="text-sm text-slate-500">{{ $reserva->reserva_label }}</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-medium ring-1 {{ $reserva->badge_class }}">
                                            {{ $reserva->badge_text }}
                                        </span>
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-3">
                                        <div class="rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200/80">
                                            <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Ruta</p>
                                            <p class="mt-1 text-base font-medium text-slate-900">{{ $reserva->ruta_resumen }}</p>
                                            <p class="mt-1 text-sm text-slate-500">{{ $reserva->origen_ciudad }} → {{ $reserva->destino_ciudad }}</p>
                                        </div>

                                        <div class="rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200/80">
                                            <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Fecha</p>
                                            <p class="mt-1 text-base font-medium text-slate-900">{{ $reserva->fecha_formateada }}</p>
                                            <p class="mt-1 text-sm text-slate-500">Salida {{ $reserva->hora_salida }}</p>
                                        </div>

                                        <div class="rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200/80">
                                            <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Asiento</p>
                                            <p class="mt-1 text-base font-medium text-slate-900">{{ $reserva->asiento }}</p>
                                            <p class="mt-1 text-sm text-slate-500">{{ $reserva->clase }} · {{ $reserva->pago_estado }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-row gap-3 xl:flex-col xl:items-end">
                                <a href="#" class="inline-flex min-w-[120px] items-center justify-center rounded-2xl bg-[#1f5cff] px-6 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700">
                                    Ver boleto
                                </a>
                                <button type="button" class="inline-flex min-w-[120px] items-center justify-center rounded-2xl bg-slate-100 px-6 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                                    Check-in
                                </button>
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="rounded-[1.5rem] bg-white p-8 text-center shadow-[0_6px_18px_rgba(15,23,42,0.12)] ring-1 ring-slate-200/70">
                        <h2 class="text-2xl font-semibold text-slate-900">Todavía no hay reservas cargadas</h2>
                        <p class="mt-3 text-slate-500">Cuando existan registros en la tabla reserva, aparecerán aquí con sus vuelos, asientos y pagos asociados.</p>
                    </article>
                @endforelse
            </div>
        </section>
    </main>

    <a href="#" class="fixed bottom-5 right-5 flex h-10 w-10 items-center justify-center rounded-full bg-[#2a2a2a] text-lg text-white shadow-lg shadow-black/25">?</a>
</body>
</html>