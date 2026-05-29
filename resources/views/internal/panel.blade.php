<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel | AeroControl</title>
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
<body class="min-h-screen bg-[#0f172a] text-white antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.25),transparent_26%),radial-gradient(circle_at_right,rgba(34,197,94,0.16),transparent_24%),linear-gradient(180deg,#0f172a_0%,#08101f_100%)]">
        <header class="border-b border-white/10 bg-white/5 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-cyan-300">AeroControl</p>
                    <h1 class="text-xl font-semibold">Panel de {{ ucfirst($user->tipo_cuenta ?? 'pasajero') }}</h1>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-white/80 transition hover:bg-white/10">Cerrar sesión</button>
                </form>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <section class="space-y-8">
                <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
                    <div>
                        <p class="section-label">Acceso autenticado</p>
                        <h2 class="mt-4 text-4xl font-bold tracking-tight sm:text-5xl">Bienvenido, {{ $user->name }}</h2>
                        <p class="mt-4 max-w-2xl text-white/70">
                            La autenticación vive en <span class="font-semibold text-white">users</span>; el perfil operativo se enlaza desde pasajero o tripulación según el tipo de cuenta.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="glass-panel rounded-[1.5rem] p-5">
                            <p class="text-sm text-white/55">Cuenta</p>
                            <p class="mt-2 text-2xl font-bold text-white">{{ ucfirst($user->tipo_cuenta ?? 'pasajero') }}</p>
                        </div>
                        <div class="glass-panel rounded-[1.5rem] p-5">
                            <p class="text-sm text-white/55">Email</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $user->email }}</p>
                        </div>
                        <div class="glass-panel rounded-[1.5rem] p-5">
                            <p class="text-sm text-white/55">Estado</p>
                            <p class="mt-2 text-lg font-semibold text-emerald-300">Activo</p>
                        </div>
                    </div>
                </div>

                @if(($user->tipo_cuenta ?? 'pasajero') === 'admin')
                    <div class="grid gap-6 md:grid-cols-3">
                        <article class="glass-panel rounded-[2rem] p-6">
                            <p class="section-label">Administración</p>
                            <h3 class="mt-3 text-2xl font-semibold">Usuarios y permisos</h3>
                            <p class="mt-3 text-white/65">Aquí puedes concentrar altas de cuentas, roles y accesos sin crear varias pantallas distintas.</p>
                        </article>
                        <article class="glass-panel rounded-[2rem] p-6">
                            <p class="section-label">Operación</p>
                            <h3 class="mt-3 text-2xl font-semibold">Vuelos y reservas</h3>
                            <p class="mt-3 text-white/65">Se puede conectar con los mismos listados que ya tienes para revisar actividad.</p>
                        </article>
                        <article class="glass-panel rounded-[2rem] p-6">
                            <p class="section-label">Tripulación</p>
                            <h3 class="mt-3 text-2xl font-semibold">Asignaciones</h3>
                            <p class="mt-3 text-white/65">Más adelante aquí puedes ver asignaciones, turnos y estados de vuelo.</p>
                        </article>
                    </div>
                @elseif(($user->tipo_cuenta ?? 'pasajero') === 'tripulacion')
                    <div class="grid gap-6 md:grid-cols-3">
                        <article class="glass-panel rounded-[2rem] p-6">
                            <p class="section-label">Tripulación</p>
                            <h3 class="mt-3 text-2xl font-semibold">Asignaciones del día</h3>
                            <p class="mt-3 text-white/65">Centraliza vuelos asignados, rol y estados operativos sin duplicar vistas.</p>
                        </article>
                        <article class="glass-panel rounded-[2rem] p-6">
                            <p class="section-label">Control</p>
                            <h3 class="mt-3 text-2xl font-semibold">Abordaje y puertas</h3>
                            <p class="mt-3 text-white/65">Aquí pueden vivir listas de abordaje, puertas y observaciones de vuelo.</p>
                        </article>
                        <article class="glass-panel rounded-[2rem] p-6">
                            <p class="section-label">Soporte</p>
                            <h3 class="mt-3 text-2xl font-semibold">Incidencias</h3>
                            <p class="mt-3 text-white/65">Puedes sumar reportes rápidos sin mezclarlo con el panel del pasajero.</p>
                        </article>
                    </div>
                @else
                    <div class="grid gap-6 md:grid-cols-3">
                        <article class="glass-panel rounded-[2rem] p-6">
                            <p class="section-label">Pasajero</p>
                            <h3 class="mt-3 text-2xl font-semibold">Mis reservas</h3>
                            <p class="mt-3 text-white/65">Enlaza aquí la vista de reservas que ya tienes montada.</p>
                        </article>
                        <article class="glass-panel rounded-[2rem] p-6">
                            <p class="section-label">Pasajero</p>
                            <h3 class="mt-3 text-2xl font-semibold">Mis vuelos</h3>
                            <p class="mt-3 text-white/65">Reutiliza el listado de vuelos para consultas rápidas.</p>
                        </article>
                        <article class="glass-panel rounded-[2rem] p-6">
                            <p class="section-label">Perfil</p>
                            <h3 class="mt-3 text-2xl font-semibold">Datos personales</h3>
                            <p class="mt-3 text-white/65">Tu perfil vive en pasajero y se relaciona con la cuenta de users.</p>
                        </article>
                    </div>
                @endif
            </section>
        </main>
    </div>
</body>
</html>