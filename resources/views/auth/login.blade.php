@extends('auth.layout', ['title' => 'Iniciar sesión | AeroControl'])

@section('content')
    <div class="grid w-full max-w-6xl gap-8 lg:grid-cols-[0.95fr_1.05fr]">
        <section class="glass-panel overflow-hidden rounded-[2.5rem] p-8 lg:p-10">
            <p class="section-label">Acceso</p>
            <h1 class="section-title mt-4">Inicia sesión en AeroControl.</h1>
            <p class="section-copy mt-5 max-w-xl">
                Accede al panel de gestión para consultar operaciones, control de vuelos y módulos internos de la aerolínea.
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-sm text-white/55">Estado</p>
                    <p class="mt-2 text-xl font-semibold">Operativo</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-sm text-white/55">Acceso</p>
                    <p class="mt-2 text-xl font-semibold">Seguro</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-sm text-white/55">Soporte</p>
                    <p class="mt-2 text-xl font-semibold">24/7</p>
                </div>
            </div>
        </section>

        <section class="glass-panel rounded-[2.5rem] p-8 lg:p-10">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-300">Inicio de sesión</p>
                <h2 class="mt-3 text-3xl font-bold text-white">Bienvenido de nuevo</h2>
                <p class="mt-3 text-white/65">Completa tus credenciales para entrar al sistema.</p>
            </div>

            <form action="{{ route('login.store') }}" method="POST" class="grid gap-5">
                @csrf
                <div>
                    <label for="login-email" class="mb-2 block text-sm text-white/70">Email</label>
                    <input id="login-email" name="email" type="email" placeholder="tu@email.com" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div>
                    <label for="login-password" class="mb-2 block text-sm text-white/70">Contraseña</label>
                    <input id="login-password" name="password" type="password" placeholder="••••••••" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div class="flex items-center justify-between gap-4 text-sm text-white/60">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded border-white/20 bg-white/10 text-cyan-400 focus:ring-cyan-300/30">
                        Recordarme
                    </label>
                    <a href="{{ route('register') }}" class="text-cyan-300 transition hover:text-cyan-200">Crear cuenta</a>
                </div>

                <button type="submit" class="primary-button rounded-full px-6 py-3 font-semibold transition duration-300">Entrar</button>
            </form>
        </section>
    </div>
@endsection
