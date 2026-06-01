<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AeroControl')</title>
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
    <script type="module" src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js"></script>
</head>
<body class="font-sans text-white antialiased">
    <div class="relative min-h-screen overflow-hidden bg-[#0f172a]">
        <!-- Efectos de fondo decorativos -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="glow-ring -left-24 top-14 h-72 w-72 bg-fuchsia-500/30"></div>
            <div class="glow-ring right-0 top-48 h-80 w-80 bg-cyan-400/20"></div>
            <div class="glow-ring left-1/2 top-136 h-96 w-96 -translate-x-1/2 bg-violet-500/20"></div>
        </div>

        <!-- Navbar -->
        <header class="sticky top-0 z-50 border-b border-white/10 bg-[#120b25]/88 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <!-- Logo y branding -->
                <a href="/" class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-linear-to-br from-indigo-500 to-cyan-400 text-lg font-black text-white shadow-lg shadow-indigo-500/30">A</span>
                    <div>
                        <p class="text-sm font-semibold tracking-[0.3em] text-cyan-300 uppercase">AeroControl</p>
                        <p class="text-xs text-white/55">Gestión integral de aerolínea</p>
                    </div>
                </a>

                <!-- Navegación desktop -->
                <nav class="hidden items-center gap-8 lg:flex">
                    <a href="/" class="text-sm text-white/75 transition hover:text-white">Inicio</a>
                    <a href="#servicios" class="text-sm text-white/75 transition hover:text-white">Servicios</a>
                    <a href="#modulos" class="text-sm text-white/75 transition hover:text-white">Módulos</a>
                    <a href="#contacto" class="text-sm text-white/75 transition hover:text-white">Contacto</a>
                </nav>

                <!-- Botones de autenticación desktop -->
                <div class="hidden items-center gap-3 lg:flex">
                    @guest
                        <a href="{{ route('iniciar.sesion') }}" class="outline-button rounded-full px-5 py-2.5 text-sm font-medium transition duration-300">Iniciar sesión</a>
                        <a href="{{ route('registro') }}" class="primary-button rounded-full px-5 py-2.5 text-sm font-semibold transition duration-300">Registro</a>
                    @endguest

                    @auth
                        @if((auth()->user()->rol ?? null) === 'admin')
                            <a href="{{ route('admin.aeronaves') }}" class="outline-button rounded-full px-5 py-2.5 text-sm font-medium transition duration-300">
                                Aeronaves
                            </a>
                            <a href="{{ route('admin.cuentas.nueva') }}" class="primary-button rounded-full px-5 py-2.5 text-sm font-semibold transition duration-300">
                                Agregar cuenta
                            </a>
                        @endif

                        <form action="{{ route('cerrar.sesion') }}" method="POST" class="inline-flex">
                            @csrf
                            <button type="submit" class="outline-button inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition duration-300">
                                <ion-icon name="log-out-sharp"></ion-icon>
                                Cerrar sesión
                            </button>
                        </form>
                    @endauth
                </div>

                <!-- Menú mobile -->
                <button id="mobile-menu-btn" aria-expanded="false" aria-controls="mobile-menu" class="relative lg:hidden rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/85 transition hover:bg-white/10">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div id="mobile-menu" class="glass-panel absolute right-0 top-16 mt-2 hidden w-64 rounded-3xl p-4 shadow-2xl shadow-black/40" role="menu" aria-hidden="true">
                    <div class="flex flex-col gap-3 text-sm">
                        @guest
                            <a href="{{ route('iniciar.sesion') }}" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Iniciar sesión</a>
                            <a href="{{ route('registro') }}" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Registro</a>
                        @endguest

                        @auth
                            @if((auth()->user()->rol ?? null) === 'admin')
                                <a href="{{ route('admin.aeronaves') }}" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Aeronaves</a>
                                <a href="{{ route('admin.cuentas.nueva') }}" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Agregar cuenta</a>
                            @endif

                            <form action="{{ route('cerrar.sesion') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full rounded-xl px-3 py-2 text-left text-white/80 transition hover:bg-white/5 hover:text-white">
                                    <span class="inline-flex items-center gap-2">
                                        <ion-icon name="log-out-sharp"></ion-icon>
                                        Cerrar sesión
                                    </span>
                                </button>
                            </form>
                        @endauth
                        <a href="/" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Inicio</a>
                        <a href="#servicios" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Servicios</a>
                        <a href="#modulos" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Módulos</a>
                        <a href="#contacto" class="rounded-xl px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">Contacto</a>
                    </div>
                </div>
            </div>
        </header>

            <!-- Contenido principal -->
            <main class="relative z-10 min-h-[calc(100vh-4rem)]">
                {{-- Hero full-width placeholder --}}
                @yield('hero')

                <!-- Contenido de la página -->
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    @yield('content')
                </div>

            <!-- Footer -->
            <footer class="relative mt-8 border-t border-white/10 bg-[#0f172a]/95 backdrop-blur-xl">
                <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Branding -->
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-linear-to-br from-indigo-500 to-cyan-400 text-lg font-black text-white shadow-lg shadow-indigo-500/30">A</span>
                            <div>
                                <p class="text-sm font-semibold text-cyan-300">AeroControl</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/55">Gestión integral de aerolínea con tecnología de vanguardia.</p>
                    </div>

                    <!-- Enlaces de producto -->
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-white/85 mb-4">Producto</h3>
                        <ul class="space-y-3 text-sm text-white/60">
                            <li><a href="#" class="transition hover:text-white">Características</a></li>
                            <li><a href="#" class="transition hover:text-white">Precios</a></li>
                            <li><a href="#" class="transition hover:text-white">Documentación</a></li>
                            <li><a href="#" class="transition hover:text-white">Roadmap</a></li>
                        </ul>
                    </div>

                    <!-- Enlaces de empresa -->
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-white/85 mb-4">Empresa</h3>
                        <ul class="space-y-3 text-sm text-white/60">
                            <li><a href="#" class="transition hover:text-white">Sobre nosotros</a></li>
                            <li><a href="#" class="transition hover:text-white">Blog</a></li>
                            <li><a href="#" class="transition hover:text-white">Carreras</a></li>
                            <li><a href="#" class="transition hover:text-white">Contacto</a></li>
                        </ul>
                    </div>

                    <!-- Enlaces legales -->
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-white/85 mb-4">Legal</h3>
                        <ul class="space-y-3 text-sm text-white/60">
                            <li><a href="#" class="transition hover:text-white">Privacidad</a></li>
                            <li><a href="#" class="transition hover:text-white">Términos</a></li>
                            <li><a href="#" class="transition hover:text-white">Cookies</a></li>
                            <li><a href="#" class="transition hover:text-white">Seguridad</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Separador -->
                <div class="border-t border-white/10 mt-8 pt-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-sm text-white/55">© 2024 AeroControl. Todos los derechos reservados.</p>
                        <div class="flex items-center gap-6">
                            <a href="#" class="text-white/55 transition hover:text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="text-white/55 transition hover:text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 002.856-3.51 10 10 0 01-2.64.551 4.482 4.482 0 001.964-2.474c-.951.564-2.005.974-3.127 1.195a4.466 4.466 0 00-7.606 4.06A12.64 12.64 0 011.892 2.566a4.466 4.466 0 001.383 5.96 4.466 4.466 0 01-2.03-.559v.06a4.466 4.466 0 003.58 4.38 4.466 4.466 0 01-2.025.077 4.467 4.467 0 004.164 3.1A8.96 8.96 0 010 19.54a12.626 12.626 0 006.847 2.01c8.216 0 12.688-6.814 12.688-12.688 0-.193-.003-.385-.009-.577a9.048 9.048 0 002.212-2.31z"/></svg>
                            </a>
                            <a href="#" class="text-white/55 transition hover:text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.475-2.236-1.986-2.236-1.081 0-1.722.731-2.004 1.438-.103.25-.129.599-.129.948v5.419h-3.554s.05-8.736 0-9.646h3.554v1.364c.43-.665 1.199-1.61 2.920-1.61 2.135 0 3.74 1.394 3.74 4.389v5.503zM5.337 8.855a2.176 2.176 0 01-2.183-2.183c0-1.205.978-2.217 2.217-2.217 1.226 0 2.184.976 2.205 2.217 0 1.205-.979 2.183-2.239 2.183zm1.918 11.597H3.42V9.807h3.835v10.645zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                </div>
            </footer>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
