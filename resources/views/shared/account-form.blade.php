@php
    $eyebrow = $eyebrow ?? 'Registro';
    $heading = $heading ?? (!empty($modo_admin) ? 'Alta de cuenta administrativa' : 'Formulario de cuenta');
    $description = $description ?? (
        !empty($modo_admin)
            ? 'Usa el selector para mostrar solo los campos que correspondan a cada tipo de cuenta.'
            : 'Completa tus datos para crear o editar la cuenta desde una sola tarjeta.'
    );
@endphp

<section class="glass-panel rounded-5xl p-8 lg:p-10">
    <div class="mb-8 space-y-3">
        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-300">{{ $eyebrow }}</p>
        <h2 class="text-3xl font-bold text-white">{{ $heading }}</h2>
        <p class="max-w-2xl text-sm leading-6 text-white/60 sm:text-base">{{ $description }}</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-400/20 bg-rose-500/10 p-4 text-sm text-rose-100">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ $action }}" method="POST" class="grid gap-5 sm:grid-cols-2">
        @csrf
        @isset($method)
            @method($method)
        @endisset

        @if(!empty($modo_admin))
            <input type="hidden" name="tipo_cuenta" value="admin">
            <div class="sm:col-span-2">
                <label for="register-role" class="mb-2 block text-sm text-white/70">Rol de la cuenta</label>
                <select id="register-role" name="rol" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white focus:border-cyan-300 focus:outline-none">
                    <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="tripulacion" {{ old('rol') === 'tripulacion' ? 'selected' : '' }}>Tripulación</option>
                    <option value="pasajero" {{ old('rol', 'pasajero') === 'pasajero' ? 'selected' : '' }}>Pasajero</option>
                </select>
            </div>

            <div id="license-group" class="{{ old('rol') === 'tripulacion' ? '' : 'hidden' }} sm:col-span-2">
                <label for="register-license" class="mb-2 block text-sm text-white/70">Número de licencia (si aplica)</label>
                <input id="register-license" name="license_number" type="text" placeholder="Licencia" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30" value="{{ old('license_number', data_get($user ?? null, 'license_number', '')) }}">
            </div>

            <div id="crew-role-group" class="{{ old('rol') === 'tripulacion' ? '' : 'hidden' }} sm:col-span-2">
                <label for="register-crew-role" class="mb-2 block text-sm text-white/70">Rol operativo de tripulación</label>
                <select id="register-crew-role" name="crew_role_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white focus:border-cyan-300 focus:outline-none">
                    <option value="">Selecciona un rol</option>
                    @foreach(($rol_tripulacion_options ?? []) as $crewRole)
                        <option value="{{ $crewRole->id_rol }}" {{ (string) old('crew_role_id') === (string) $crewRole->id_rol ? 'selected' : '' }}>
                            {{ $crewRole->nombre_rol }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-2 text-xs text-white/45">Se usa para identificar si la persona es piloto, copiloto, sobrecargo, ingeniero, etc.</p>
            </div>
        @endif

        <div class="sm:col-span-2">
            <label for="register-name" class="mb-2 block text-sm text-white/70">Nombre completo</label>
            <input id="register-name" name="name" type="text" placeholder="Nombre y apellidos" autocomplete="name" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30" value="{{ old('name', data_get($user ?? null, 'nombre', '')) }}">
        </div>

        <div>
            <label for="register-email" class="mb-2 block text-sm text-white/70">Correo electrónico</label>
            <input id="register-email" name="email" type="email" placeholder="tu@email.com" autocomplete="email" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30" value="{{ old('email', data_get($user ?? null, 'email', '')) }}">
        </div>

        <div>
            <label for="register-phone" class="mb-2 block text-sm text-white/70">Teléfono</label>
            <input id="register-phone" name="phone" type="tel" placeholder="+34 600 000 000" autocomplete="tel" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30" value="{{ old('phone', data_get($user ?? null, 'telefono', '')) }}">
        </div>

        <div class="sm:col-span-2">
            <p class="text-sm text-white/55">
                {{ !empty($modo_admin) ? 'Esta alta crea una cuenta de administrador. No es accesible desde el registro público.' : 'El correo es obligatorio porque la autenticación vive en users. El teléfono sigue siendo opcional para el perfil del pasajero.' }}
            </p>
        </div>

        {{-- Passport & nationality: shown by default for public register, or if admin selects "pasajero" --}}
        <div id="passport-group" class="{{ empty($modo_admin) || old('rol', 'pasajero') === 'pasajero' ? '' : 'hidden' }} sm:col-span-2">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="register-passport" class="mb-2 block text-sm text-white/70">Número de pasaporte</label>
                    <input id="register-passport" name="passport_number" type="text" placeholder="Pasaporte" autocomplete="off" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30" value="{{ old('passport_number', data_get($user ?? null, 'pasaporte', '')) }}">
                </div>

                <div>
                    <label for="register-nationality" class="mb-2 block text-sm text-white/70">Nacionalidad</label>
                    <input id="register-nationality" name="nationality" type="text" placeholder="Ej. Española" autocomplete="country-name" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30" value="{{ old('nationality', data_get($user ?? null, 'nacionalidad', '')) }}">
                </div>
            </div>
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
            <button type="submit" class="primary-button rounded-full px-6 py-3 font-semibold transition duration-300">{{ $submitText ?? 'Guardar' }}</button>
        </div>
    </form>
</section>