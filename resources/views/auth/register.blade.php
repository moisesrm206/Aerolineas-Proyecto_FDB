@extends('auth.layout', ['title' => 'Registro | AeroControl'])

@section('content')
    <div class="grid w-full max-w-6xl gap-8 lg:grid-cols-[1.05fr_0.95fr]">
        <section class="glass-panel rounded-[2.5rem] p-8 lg:p-10">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-300">Registro</p>
                <h1 class="mt-3 text-3xl font-bold text-white">Crea una cuenta nueva</h1>
                <p class="mt-3 text-white/65">Registra un usuario para comenzar a operar dentro de la plataforma.</p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="grid gap-5">
                @csrf
                <div>
                    <label for="register-name" class="mb-2 block text-sm text-white/70">Nombre completo</label>
                    <input id="register-name" name="name" type="text" placeholder="Tu nombre" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div>
                    <label for="register-email" class="mb-2 block text-sm text-white/70">Email</label>
                    <input id="register-email" name="email" type="email" placeholder="tu@email.com" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div>
                    <label for="register-password" class="mb-2 block text-sm text-white/70">Contraseña</label>
                    <input id="register-password" name="password" type="password" placeholder="••••••••" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div>
                    <label for="register-password-confirm" class="mb-2 block text-sm text-white/70">Confirmar contraseña</label>
                    <input id="register-password-confirm" name="password_confirmation" type="password" placeholder="••••••••" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <button type="submit" class="primary-button rounded-full px-6 py-3 font-semibold transition duration-300">Registrarme</button>
            </form>
        </section>

        <section class="glass-panel overflow-hidden rounded-[2.5rem] p-8 lg:p-10">
            <p class="section-label">Bienvenida</p>
            <h2 class="section-title mt-4">Todo listo para tu equipo.</h2>
            <p class="section-copy mt-5 max-w-xl">
                Usa este formulario como base para el flujo de alta de usuarios y adapta los campos cuando se defina la lógica real de autenticación.
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-sm text-white/55">Usuarios</p>
                    <p class="mt-2 text-xl font-semibold">Nuevos</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-sm text-white/55">Proceso</p>
                    <p class="mt-2 text-xl font-semibold">Simple</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-sm text-white/55">Base</p>
                    <p class="mt-2 text-xl font-semibold">Escalable</p>
                </div>
            </div>

            <div class="mt-8 rounded-[1.75rem] border border-white/10 bg-[linear-gradient(135deg,rgba(34,211,238,0.14),rgba(124,58,237,0.18))] p-6">
                <p class="text-sm text-white/60">¿Ya tienes cuenta?</p>
                <a href="{{ route('login') }}" class="mt-2 inline-flex text-sm font-semibold text-cyan-300 transition hover:text-cyan-200">Volver a iniciar sesión</a>
            </div>
        </section>
    </div>
@endsection
