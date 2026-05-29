@extends('templates.app')

@section('title', 'Inicio - AeroControl')

@section('content')
    <section class="mx-auto max-w-7xl px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pt-14">
        <div class = "grid items-center gap-10 lg:grid-cols-[0.95fr_1.05fr] width full ">
            <h1 class="text-4xl font-bold text-white">Vuela a donde quieras con AeroVuelos</h1>
            <p class="text-lg text-white/70">Las mejores tarifas el mejor servicio, destinos increibles</p>
        </div>
        <div class="grid items-center gap-10 lg:grid-cols-[1.08fr_0.92fr]">
            <div class="space-y-8">
                <div class="flex flex-col gap-4 sm:flex-row">
                    <a href="#contacto" class="primary-button inline-flex items-center justify-center rounded-2xl px-8 py-4 text-sm font-semibold text-white transition duration-300">
                        Comienza tu viaje
                    </a>
                    <a href="{{ route('vuelos.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-8 py-4 text-sm font-semibold text-white transition duration-300 hover:bg-white/15">
                        Ver vuelos
                    </a>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="glass-panel rounded-[1.75rem] p-5">
                        <p class="text-sm text-white/55">Destinos</p>
                        <p class="mt-2 text-3xl font-bold text-white">100+</p>
                        <p class="mt-2 text-sm text-white/70">Ciudades conectadas en todo el mundo</p>
                    </div>
                    <div class="glass-panel rounded-[1.75rem] p-5">
                        <p class="text-sm text-white/55">Puntualidad</p>
                        <p class="mt-2 text-3xl font-bold text-white">95%</p>
                        <p class="mt-2 text-sm text-white/70">Salidas a tiempo y atención confiable</p>
                    </div>
                    <div class="glass-panel rounded-[1.75rem] p-5">
                        <p class="text-sm text-white/55">Soporte</p>
                        <p class="mt-2 text-3xl font-bold text-white">24/7</p>
                        <p class="mt-2 text-sm text-white/70">Asistencia antes, durante y después del viaje</p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -left-10 top-8 h-28 w-28 rounded-full bg-cyan-400/25 blur-3xl"></div>
                <div class="absolute -right-8 bottom-12 h-32 w-32 rounded-full bg-indigo-500/25 blur-3xl"></div>

                <div class="glass-panel overflow-hidden rounded-[2.5rem] p-5 shadow-[0_30px_90px_rgba(0,0,0,0.35)]">
                    <div class="rounded-4xl bg-[linear-gradient(180deg,#3d7dff_0%,#1d56eb_100%)] p-6 text-white">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-white/80">Tu próxima aventura</p>
                                <h2 class="mt-2 text-2xl font-bold">Madrid · Miami · Tokio</h2>
                            </div>
                            <div class="rounded-2xl bg-white/15 px-4 py-2 text-right backdrop-blur">
                                <p class="text-xs text-white/75">Desde</p>
                                <p class="text-lg font-bold">$4,500</p>
                            </div>
                        </div>

                        <div class="mt-8 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-white/65">Salida</p>
                                <p class="mt-2 text-lg font-semibold">08:45 AM</p>
                            </div>
                            <div class="rounded-3xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-white/65">Duración</p>
                                <p class="mt-2 text-lg font-semibold">8h 30m</p>
                            </div>
                            <div class="rounded-3xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-white/65">Estado</p>
                                <p class="mt-2 text-lg font-semibold">Disponible</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-5">
                            <p class="text-sm text-white/55">Check-in digital</p>
                            <p class="mt-3 text-xl font-semibold text-white">Más rápido, más simple</p>
                            <p class="mt-3 text-sm leading-6 text-white/70">Gestiona tus vuelos y tu equipaje sin filas innecesarias.</p>
                        </div>
                        <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-5">
                            <p class="text-sm text-white/55">Selección de asiento</p>
                            <p class="mt-3 text-xl font-semibold text-white">Tu viaje, tu comodidad</p>
                            <p class="mt-3 text-sm leading-6 text-white/70">Elige la mejor ubicación antes de abordar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="section-label">Ventajas</p>
            <h2 class="section-title mt-4">¿Por qué volar con nosotros?</h2>
        </div>

        <div class="mt-14 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <article class="glass-panel rounded-4xl p-6 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-3xl text-blue-600">🌐</div>
                <h3 class="mt-6 text-xl font-semibold text-white">Destinos globales</h3>
                <p class="mt-3 text-sm leading-7 text-white/70">Más de 100 destinos en todo el mundo.</p>
            </article>

            <article class="glass-panel rounded-4xl p-6 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-3xl text-blue-600">🛡️</div>
                <h3 class="mt-6 text-xl font-semibold text-white">Seguridad garantizada</h3>
                <p class="mt-3 text-sm leading-7 text-white/70">Los más altos estándares de seguridad.</p>
            </article>

            <article class="glass-panel rounded-4xl p-6 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-3xl text-blue-600">🕒</div>
                <h3 class="mt-6 text-xl font-semibold text-white">Puntualidad</h3>
                <p class="mt-3 text-sm leading-7 text-white/70">95% de nuestros vuelos salen a tiempo.</p>
            </article>

            <article class="glass-panel rounded-4xl p-6 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-3xl text-blue-600">✈️</div>
                <h3 class="mt-6 text-xl font-semibold text-white">Flota moderna</h3>
                <p class="mt-3 text-sm leading-7 text-white/70">Aeronaves de última generación.</p>
            </article>
        </div>
    </section>

    <section id="destinos" class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="section-label">Explora</p>
            <h2 class="section-title mt-4">Destinos populares</h2>
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            <article class="overflow-hidden rounded-[1.75rem] bg-white shadow-[0_12px_35px_rgba(15,23,42,0.12)]">
                <div class="h-56 bg-[linear-gradient(180deg,#4e8cff_0%,#1d56eb_100%)]"></div>
                <div class="p-6">
                    <p class="text-2xl font-semibold text-slate-200/70">Los Ángeles</p>
                    <p class="mt-2 text-sm text-slate-600">Estados Unidos</p>
                    <div class="mt-6 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-lg text-slate-500">Desde</p>
                            <p class="text-3xl font-semibold text-blue-600">$4,500</p>
                        </div>
                        <a href="{{ route('vuelos.index') }}" class="text-blue-600 transition hover:text-blue-500">Ver vuelos →</a>
                    </div>
                </div>
            </article>

            <article class="overflow-hidden rounded-[1.75rem] bg-white shadow-[0_12px_35px_rgba(15,23,42,0.12)]">
                <div class="h-56 bg-[linear-gradient(180deg,#4e8cff_0%,#1d56eb_100%)]"></div>
                <div class="p-6">
                    <p class="text-2xl font-semibold text-slate-200/70">Miami</p>
                    <p class="mt-2 text-sm text-slate-600">Estados Unidos</p>
                    <div class="mt-6 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-lg text-slate-500">Desde</p>
                            <p class="text-3xl font-semibold text-blue-600">$5,200</p>
                        </div>
                        <a href="{{ route('vuelos.index') }}" class="text-blue-600 transition hover:text-blue-500">Ver vuelos →</a>
                    </div>
                </div>
            </article>

            <article class="overflow-hidden rounded-[1.75rem] bg-white shadow-[0_12px_35px_rgba(15,23,42,0.12)]">
                <div class="h-56 bg-[linear-gradient(180deg,#4e8cff_0%,#1d56eb_100%)]"></div>
                <div class="p-6">
                    <p class="text-2xl font-semibold text-slate-200/70">Madrid</p>
                    <p class="mt-2 text-sm text-slate-600">España</p>
                    <div class="mt-6 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-lg text-slate-500">Desde</p>
                            <p class="text-3xl font-semibold text-blue-600">$12,500</p>
                        </div>
                        <a href="{{ route('vuelos.index') }}" class="text-blue-600 transition hover:text-blue-500">Ver vuelos →</a>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <section id="contacto" class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
            <div class="rounded-[2.5rem] border border-white/10 bg-[linear-gradient(180deg,rgba(124,58,237,0.18),rgba(15,23,42,0.2))] p-8 lg:p-10">
                <p class="section-label">Contacto</p>
                <h2 class="section-title mt-4">¿Listo para conectar el sistema con sus procesos reales?</h2>
                <p class="section-copy mt-5">
                    Esta sección puede crecer después con formulario funcional, integración de correo o enlace directo a soporte.
                </p>
                <div class="mt-8 space-y-4 text-sm text-white/70">
                    <p>Correo: contacto@aerolineas.com</p>
                    <p>Teléfono: +34 123 456 789</p>
                    <p>Horario: Lunes a viernes, 8:00 a 18:00</p>
                </div>
            </div>

            <form class="glass-panel rounded-[2.5rem] p-8 lg:p-10">
                <div class="grid gap-5">
                    <div>
                        <label class="mb-2 block text-sm text-white/70">Nombre</label>
                        <input type="text" placeholder="Tu nombre" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-white/70">Email</label>
                        <input type="email" placeholder="tu@email.com" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-white/70">Mensaje</label>
                        <textarea rows="5" placeholder="Cuéntanos qué necesitas" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30"></textarea>
                    </div>
                    <button type="submit" class="primary-button rounded-full px-6 py-3 font-semibold transition duration-300">Enviar mensaje</button>
                </div>
            </form>
        </div>
    </section>
@endsection