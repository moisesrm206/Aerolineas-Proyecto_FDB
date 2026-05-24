<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AeroControl | Gestión Integral de Aerolínea</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-white antialiased">
    <div class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="glow-ring -left-24 top-14 h-72 w-72 bg-fuchsia-500/30"></div>
            <div class="glow-ring right-0 top-48 h-80 w-80 bg-cyan-400/20"></div>
            <div class="glow-ring left-1/2 top-136 h-96 w-96 -translate-x-1/2 bg-violet-500/20"></div>
        </div>

        <header class="sticky top-0 z-50 border-b border-white/10 bg-[#120b25]/88 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="#inicio" class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-linear-to-br from-indigo-500 to-cyan-400 text-lg font-black text-white shadow-lg shadow-indigo-500/30">A</span>
                    <div>
                        <p class="text-sm font-semibold tracking-[0.3em] text-cyan-300 uppercase">AeroControl</p>
                        <p class="text-xs text-white/55">Gestión integral de aerolínea</p>
                    </div>
                </a>

                <nav class="hidden items-center gap-8 lg:flex">
                    <a href="#inicio" class="text-sm text-white/75 transition hover:text-white">Inicio</a>
                    <a href="#servicios" class="text-sm text-white/75 transition hover:text-white">Servicios</a>
                    <a href="#modulos" class="text-sm text-white/75 transition hover:text-white">Módulos</a>
                    <a href="#contacto" class="text-sm text-white/75 transition hover:text-white">Contacto</a>
                </nav>

                <div class="hidden items-center gap-3 lg:flex">
                    <a href="{{ route('login') }}" class="outline-button rounded-full px-5 py-2.5 text-sm font-medium transition duration-300">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="primary-button rounded-full px-5 py-2.5 text-sm font-semibold transition duration-300">Registro</a>
                    <a href="#modulos" class="outline-button rounded-full px-5 py-2.5 text-sm font-medium transition duration-300">Explorar</a>
                    <a href="#contacto" class="primary-button rounded-full px-5 py-2.5 text-sm font-semibold transition duration-300">Solicitar demo</a>
                </div>

                <details class="relative lg:hidden">
                    <summary class="list-none cursor-pointer rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/85">
                        Menú
                    </summary>
                    <div class="glass-panel absolute right-0 mt-3 w-64 rounded-3xl p-4 shadow-2xl shadow-black/40">
                        <div class="flex flex-col gap-3 text-sm">
                            <a href="{{ route('login') }}" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Iniciar sesión</a>
                            <a href="{{ route('register') }}" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Registro</a>
                            <a href="#inicio" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Inicio</a>
                            <a href="#servicios" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Servicios</a>
                            <a href="#modulos" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Módulos</a>
                            <a href="#contacto" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Contacto</a>
                            <a href="#contacto" class="primary-button mt-2 rounded-full px-4 py-3 text-center font-semibold transition duration-300">Solicitar demo</a>
                        </div>
                    </div>
                </details>
            </div>
        </header>

        <main id="inicio">
            <section class="relative mx-auto max-w-7xl px-4 pb-24 pt-12 sm:px-6 lg:px-8 lg:pt-16">
                <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="relative z-10 max-w-2xl">
                        

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="#contacto" class="primary-button inline-flex items-center justify-center rounded-full px-7 py-3.5 text-sm font-semibold transition duration-300">
                                Reserva una demo
                            </a>
                            <a href="#modulos" class="outline-button inline-flex items-center justify-center rounded-full px-7 py-3.5 text-sm font-semibold transition duration-300">
                                Ver módulos
                            </a>
                            <a href="{{ route('login') }}" class="outline-button inline-flex items-center justify-center rounded-full px-7 py-3.5 text-sm font-semibold transition duration-300">
                                Acceder
                            </a>
                        </div>

                        <div class="mt-10 grid gap-4 sm:grid-cols-3">
                            <div class="glass-panel rounded-[1.6rem] p-5">
                                <p class="text-sm text-white/55">Vuelos activos</p>
                                <p class="mt-3 text-3xl font-bold">148</p>
                                <p class="mt-2 text-sm text-emerald-300">+12% esta semana</p>
                            </div>
                            <div class="glass-panel rounded-[1.6rem] p-5">
                                <p class="text-sm text-white/55">Flotilla disponible</p>
                                <p class="mt-3 text-3xl font-bold">92%</p>
                                <p class="mt-2 text-sm text-cyan-300">Control operativo estable</p>
                            </div>
                            <div class="glass-panel rounded-[1.6rem] p-5">
                                <p class="text-sm text-white/55">Satisfacción</p>
                                <p class="mt-3 text-3xl font-bold">4.9/5</p>
                                <p class="mt-2 text-sm text-fuchsia-300">Atención y puntualidad</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-6 top-8 h-24 w-24 rounded-full bg-cyan-400/25 blur-3xl"></div>
                        <div class="absolute -right-2 bottom-10 h-32 w-32 rounded-full bg-fuchsia-500/20 blur-3xl"></div>

                        <div class="glass-panel relative overflow-hidden rounded-[2.25rem] p-5 shadow-[0_30px_120px_rgba(0,0,0,0.45)]">
                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(56,189,248,0.16),transparent_35%),radial-gradient(circle_at_bottom_left,rgba(168,85,247,0.22),transparent_30%)]"></div>
                            <div class="relative rounded-[1.7rem] border border-white/10 bg-white/5 p-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.28em] text-cyan-200/80">Panel de control</p>
                                        <p class="mt-2 text-xl font-semibold text-white">Ruta Madrid → Bogotá</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/10 px-4 py-2 text-right">
                                        <p class="text-xs text-white/55">Estado</p>
                                        <p class="text-sm font-semibold text-emerald-300">A tiempo</p>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-4 sm:grid-cols-[1.2fr_0.8fr]">
                                    <div class="rounded-3xl border border-white/10 bg-[#18112f]/85 p-5">
                                        <div class="flex items-center justify-between text-sm text-white/60">
                                            <span>Próximo despegue</span>
                                            <span>08:45</span>
                                        </div>
                                        <div class="mt-6 flex items-end justify-between gap-4">
                                            <div>
                                                <p class="text-3xl font-black text-white">AV-214</p>
                                                <p class="mt-2 text-sm text-white/60">Capacidad: 184 pasajeros</p>
                                            </div>
                                            <div class="rounded-[1.4rem] bg-linear-to-br from-indigo-500 to-cyan-400 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-cyan-500/20">
                                                Embarque
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                        <p class="text-sm text-white/55">Ocupación</p>
                                        <div class="mt-4 flex items-end gap-3">
                                            <span class="text-4xl font-black text-white">87%</span>
                                            <span class="pb-1 text-sm text-emerald-300">+4.1% vs ayer</span>
                                        </div>
                                        <div class="mt-5 h-2 overflow-hidden rounded-full bg-white/10">
                                            <div class="h-full w-[87%] rounded-full bg-linear-to-r from-cyan-400 via-indigo-400 to-fuchsia-400"></div>
                                        </div>
                                        <div class="mt-5 flex items-center justify-between text-xs text-white/55">
                                            <span>Check-in</span>
                                            <span>Abierto</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-[1.25rem] border border-white/10 bg-white/5 p-4">
                                        <p class="text-xs uppercase tracking-[0.2em] text-white/45">Tripulación</p>
                                        <p class="mt-2 text-lg font-semibold">18 asignados</p>
                                    </div>
                                    <div class="rounded-[1.25rem] border border-white/10 bg-white/5 p-4">
                                        <p class="text-xs uppercase tracking-[0.2em] text-white/45">Clima</p>
                                        <p class="mt-2 text-lg font-semibold">Óptimo</p>
                                    </div>
                                    <div class="rounded-[1.25rem] border border-white/10 bg-white/5 p-4">
                                        <p class="text-xs uppercase tracking-[0.2em] text-white/45">Soporte</p>
                                        <p class="mt-2 text-lg font-semibold">24/7 activo</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="servicios" class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
                    <div class="glass-panel rounded-4xl p-8 lg:p-10">
                        <p class="section-label">Servicios</p>
                        <h2 class="section-title mt-4">Una experiencia pensada para verse bien y sentirse seria.</h2>
                        <p class="section-copy mt-5">
                            El enfoque visual evita el estilo genérico y apuesta por una composición más editorial, con contraste fuerte, bloques definidos y jerarquía clara.
                        </p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <article class="glass-panel rounded-4xl p-6">
                            <div class="h-40 rounded-3xl bg-[linear-gradient(135deg,rgba(34,211,238,0.25),rgba(79,70,229,0.2)),radial-gradient(circle_at_top,rgba(255,255,255,0.12),transparent_55%)]"></div>
                            <h3 class="mt-5 text-2xl font-semibold">Operación clara</h3>
                            <p class="mt-3 text-white/68">Tableros, estados y métricas con lectura rápida para el equipo.</p>
                        </article>

                        <article class="glass-panel rounded-4xl p-6">
                            <div class="h-40 rounded-3xl bg-[linear-gradient(135deg,rgba(168,85,247,0.25),rgba(34,197,94,0.18)),radial-gradient(circle_at_top,rgba(255,255,255,0.12),transparent_55%)]"></div>
                            <h3 class="mt-5 text-2xl font-semibold">Imagen premium</h3>
                            <p class="mt-3 text-white/68">Colores sobrios, glassmorphism sutil y bloques visuales más sólidos.</p>
                        </article>
                    </div>
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
        </main>

        <footer class="border-t border-white/10 bg-black/10">
            <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-6 text-sm text-white/55 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <p>© 2026 AeroControl. Todos los derechos reservados.</p>
                <p>Landing page en Laravel con Tailwind CSS y Vite.</p>
            </div>
        </footer>
    </div>
</body>
</html>