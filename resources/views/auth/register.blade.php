@extends('templates.layout', ['title' => 'Registro | AeroControl'])

@section('content')
    <div class="grid w-full max-w-6xl gap-8 lg:grid-cols-[1.05fr_0.95fr]">
        <section class="glass-panel rounded-[2.5rem] p-8 lg:p-10">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-300">Registro de cliente</p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="grid gap-5 sm:grid-cols-2">
                @csrf

                <div class="sm:col-span-2">
                    <label for="register-name" class="mb-2 block text-sm text-white/70">Nombre completo</label>
                    <input id="register-name" name="name" type="text" placeholder="Nombre y apellidos" autocomplete="name" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div>
                    <label for="register-email" class="mb-2 block text-sm text-white/70">Correo electrónico</label>
                    <input id="register-email" name="email" type="email" placeholder="tu@email.com" autocomplete="email" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div>
                    <label for="register-phone" class="mb-2 block text-sm text-white/70">Teléfono</label>
                    <input id="register-phone" name="phone" type="tel" placeholder="+34 600 000 000" autocomplete="tel" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div class="sm:col-span-2">
                    <p class="text-sm text-white/55">Completa al menos correo electrónico o teléfono para cumplir con el registro de contacto del pasajero.</p>
                </div>

                <div>
                    <label for="register-passport" class="mb-2 block text-sm text-white/70">Número de pasaporte</label>
                    <input id="register-passport" name="passport_number" type="text" placeholder="Pasaporte" autocomplete="off" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div>
                    <label for="register-nationality" class="mb-2 block text-sm text-white/70">Nacionalidad</label>
                    <input id="register-nationality" name="nationality" type="text" placeholder="Ej. Española" autocomplete="country-name" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div>
                    <label for="register-password" class="mb-2 block text-sm text-white/70">Contraseña</label>
                    <input id="register-password" name="password" type="password" placeholder="••••••••" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div>
                    <label for="register-password-confirm" class="mb-2 block text-sm text-white/70">Confirmar contraseña</label>
                    <input id="register-password-confirm" name="password_confirmation" type="password" placeholder="••••••••" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div class="sm:col-span-2">
                    <button type="submit" class="primary-button rounded-full px-6 py-3 font-semibold transition duration-300">Guardar cliente</button>
                </div>
            </form>
        </section>

        <section class="glass-panel overflow-hidden rounded-[2.5rem] p-8 lg:p-10">
            <p class="section-label">Atributos</p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-sm text-white/55">Identidad</p>
                    <p class="mt-2 text-xl font-semibold">Nombre y pasaporte</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-sm text-white/55">Contacto</p>
                    <p class="mt-2 text-xl font-semibold">Email o teléfono</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-sm text-white/55">Perfil</p>
                    <p class="mt-2 text-xl font-semibold">Nacionalidad</p>
                </div>
            </div>

            <div class="mt-8 rounded-[1.75rem] border border-white/10 bg-[linear-gradient(135deg,rgba(34,211,238,0.14),rgba(124,58,237,0.18))] p-6">
                <a href="{{ route('login') }}" class="mt-2 inline-flex text-sm font-semibold text-cyan-300 transition hover:text-cyan-200">Volver a iniciar sesión</a>
            </div>
        </section>
    </div>
@endsection
