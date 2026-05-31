@extends('templates.form_in_and_out', ['title' => 'Iniciar sesión | AeroControl'])

@section('content')
    <section class="glass-panel rounded-5xl p-8 lg:p-10">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-300">Inicio de sesión</p>
            <h2 class="mt-3 text-3xl font-bold text-white">Bienvenido de nuevo</h2>
            <p class="mt-3 text-white/65">Completa tus credenciales para entrar al sistema.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-400/20 bg-rose-500/10 p-4 text-sm text-rose-100">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('iniciar.sesion.guardar') }}" method="POST" class="grid gap-5">
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
                <a href="{{ route('registro') }}" class="text-cyan-300 transition hover:text-cyan-200">Crear cuenta</a>
            </div>

            <button type="submit" class="primary-button rounded-full px-6 py-3 font-semibold transition duration-300">Entrar</button>
        </form>
    </section>
@endsection