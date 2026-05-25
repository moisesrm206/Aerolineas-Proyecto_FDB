<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'AeroControl' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-white antialiased">
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="glow-ring -left-24 top-20 h-72 w-72 bg-fuchsia-500/25"></div>
            <div class="glow-ring right-0 top-44 h-80 w-80 bg-cyan-400/20"></div>
        </div>

        <header class="relative z-10 mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-linear-to-br from-indigo-500 to-cyan-400 text-lg font-black text-white shadow-lg shadow-indigo-500/30">A</span>
                <div>
                    <p class="text-sm font-semibold tracking-[0.3em] text-cyan-300 uppercase">AeroControl</p>
                    <p class="text-xs text-white/55">Gestión integral de aerolínea</p>
                </div>
            </a>
            <a href="{{ route('home') }}" class="outline-button rounded-full px-5 py-2.5 text-sm font-semibold transition duration-300">Volver al inicio</a>
        </header>

        <main class="relative z-10 flex items-center justify-center px-4 pb-16 pt-4 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
