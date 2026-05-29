@extends('templates.form_in_and_out', ['title' => 'Registro | AeroControl'])

@section('content')
    <div class="w-full flex items-center justify-center">
        <div class="w-full max-w-xl px-4">
            <section class="glass-panel rounded-[2.5rem] p-8 lg:p-10">
                <div class="mb-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-300">Registro</p>
                    @if(!empty($modo_admin))
                        <h2 class="mt-3 text-3xl font-bold text-white">Alta de cuenta administrativa</h2>
                    @endif
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-400/20 bg-rose-500/10 p-4 text-sm text-rose-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ !empty($modo_admin) ? route('admin.cuentas.store') : route('register.store') }}" method="POST" class="grid gap-5 sm:grid-cols-2">
                    @csrf

                    @if(!empty($modo_admin))
                        <input type="hidden" name="tipo_cuenta" value="admin">
                    @endif

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
                        <p class="text-sm text-white/55">
                            {{ !empty($modo_admin) ? 'Esta alta crea una cuenta de administrador. No es accesible desde el registro público.' : 'El correo es obligatorio porque la autenticación vive en users. El teléfono sigue siendo opcional para el perfil del pasajero.' }}
                        </p>
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
        </div>
    </div>
@endsection