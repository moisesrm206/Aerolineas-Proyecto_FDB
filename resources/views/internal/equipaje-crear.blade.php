@extends('templates.app')

@section('title', 'Registrar equipaje | AeroControl')

@section('hero')
    @include('shared.page-hero', [
        'label' => 'Operación',
        'title' => 'Registrar equipaje',
        'subtitle' => 'Crea una etiqueta, asóciala a un pasajero y deja listo el seguimiento.',
    ])
@endsection

@section('content')
    <section class="space-y-8">
        @if(session('status'))
            <div class="rounded-3xl border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-medium text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->has('general'))
            <div class="rounded-3xl border border-rose-300/20 bg-rose-300/10 px-5 py-4 text-sm font-medium text-rose-100">
                {{ $errors->first('general') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/55">Etiqueta</p>
                <p class="mt-2 text-2xl font-bold text-white">Auto generada</p>
                <p class="mt-2 text-sm text-cyan-300">Se crea al guardar</p>
            </article>

            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/55">Estados</p>
                <p class="mt-2 text-2xl font-bold text-white">6 opciones</p>
                <p class="mt-2 text-sm text-amber-300">Para simular seguimiento</p>
            </article>

            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/55">Tipo</p>
                <p class="mt-2 text-2xl font-bold text-white">Mano / bodega</p>
                <p class="mt-2 text-sm text-emerald-300">Un solo registro</p>
            </article>

            <article class="glass-panel rounded-3xl p-5">
                <p class="text-sm text-white/55">Uso</p>
                <p class="mt-2 text-2xl font-bold text-white">Operación</p>
                <p class="mt-2 text-sm text-violet-300">Registro interno</p>
            </article>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <section class="glass-panel rounded-4xl p-6 sm:p-8">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="section-label">Nuevo equipaje</p>
                        <h2 class="mt-4 text-3xl font-bold text-white">Alta operativa</h2>
                        <p class="mt-3 max-w-3xl text-white/65">
                            Registra el equipaje desde operación o check-in. Si eliges un boleto, debe pertenecer al pasajero seleccionado.
                        </p>
                    </div>
                    <a href="{{ route('admin.panel') }}" class="outline-button inline-flex items-center rounded-2xl px-5 py-3 text-sm font-semibold transition duration-300">
                        Volver al panel
                    </a>
                </div>

                <form action="{{ route('admin.equipaje.store') }}" method="POST" class="mt-8 grid gap-5 md:grid-cols-2">
                    @csrf

                    <label class="space-y-2 md:col-span-2">
                        <span class="text-sm font-medium text-white/80">Pasajero</span>
                        <select name="id_pasajero" class="w-full rounded-2xl border {{ $errors->has('id_pasajero') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            <option value="">Selecciona un pasajero</option>
                            @foreach($pasajeros as $pasajero)
                                <option value="{{ $pasajero->id_pasajero }}" @selected(old('id_pasajero') == $pasajero->id_pasajero)>
                                    {{ $pasajero->usuario?->nombre ?? 'Sin nombre' }} · Pasajero #{{ $pasajero->id_pasajero }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_pasajero')
                            <p class="text-sm text-rose-200">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="space-y-2 md:col-span-2">
                        <span class="text-sm font-medium text-white/80">Boleto asociado</span>
                        <select name="id_boleto" class="w-full rounded-2xl border {{ $errors->has('id_boleto') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            <option value="">Sin boleto</option>
                            @foreach($boletos as $boleto)
                                @php
                                    $origen = $boleto->reserva?->vuelo?->ruta?->aeropuertoOrigen?->codigo_iata ?? 'N/D';
                                    $destino = $boleto->reserva?->vuelo?->ruta?->aeropuertoDestino?->codigo_iata ?? 'N/D';
                                    $pasajeroNombre = $boleto->reserva?->pasajero?->usuario?->nombre ?? 'Sin pasajero';
                                @endphp
                                <option value="{{ $boleto->id_boleto }}" @selected(old('id_boleto') == $boleto->id_boleto)>
                                    #{{ $boleto->id_boleto }} · {{ $pasajeroNombre }} · {{ $origen }} → {{ $destino }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-white/45">Si eliges un boleto, se validará que pertenezca al pasajero seleccionado.</p>
                        @error('id_boleto')
                            <p class="text-sm text-rose-200">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="space-y-2">
                        <span class="text-sm font-medium text-white/80">Tipo de equipaje</span>
                        <select name="tipo_equipaje" class="w-full rounded-2xl border {{ $errors->has('tipo_equipaje') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            <option value="mano" @selected(old('tipo_equipaje') === 'mano')>Mano</option>
                            <option value="bodega" @selected(old('tipo_equipaje') === 'bodega')>Bodega</option>
                        </select>
                        @error('tipo_equipaje')
                            <p class="text-sm text-rose-200">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="space-y-2">
                        <span class="text-sm font-medium text-white/80">Peso (kg)</span>
                        <input type="number" step="0.01" min="0.01" name="peso_kg" value="{{ old('peso_kg') }}" class="w-full rounded-2xl border {{ $errors->has('peso_kg') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                        @error('peso_kg')
                            <p class="text-sm text-rose-200">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="space-y-2 md:col-span-2">
                        <span class="text-sm font-medium text-white/80">Estado</span>
                        <select name="estado" class="w-full rounded-2xl border {{ $errors->has('estado') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            @php($estadoActual = old('estado', 'registrado'))
                            <option value="registrado" @selected($estadoActual === 'registrado')>Registrado</option>
                            <option value="en_cinta" @selected($estadoActual === 'en_cinta')>En cinta</option>
                            <option value="cargado" @selected($estadoActual === 'cargado')>Cargado</option>
                            <option value="en_transito" @selected($estadoActual === 'en_transito')>En tránsito</option>
                            <option value="entregado" @selected($estadoActual === 'entregado')>Entregado</option>
                            <option value="perdido" @selected($estadoActual === 'perdido')>Perdido</option>
                        </select>
                        @error('estado')
                            <p class="text-sm text-rose-200">{{ $message }}</p>
                        @enderror
                    </label>

                    <div class="md:col-span-2 flex flex-wrap items-center justify-between gap-3 pt-2">
                        <p class="text-sm text-white/55">La etiqueta se generará automáticamente usando el ID del registro.</p>
                        <button type="submit" class="primary-button inline-flex items-center rounded-2xl px-6 py-3 text-sm font-semibold text-white transition duration-300">
                            Registrar equipaje
                        </button>
                    </div>
                </form>
            </section>

            <aside class="space-y-6">
                <section class="glass-panel rounded-4xl p-6 sm:p-8">
                    <p class="section-label">Consejo</p>
                    <h3 class="mt-3 text-2xl font-semibold text-white">Quién registra el equipaje</h3>
                    <p class="mt-4 text-sm leading-7 text-white/75">
                        Lo más lógico es que lo registre operación o check-in, no el pasajero. Así mantienes control sobre la etiqueta, el peso y el estado, y el pasajero solo consulta el seguimiento.
                    </p>
                </section>

                <section class="rounded-4xl border border-amber-300/40 bg-[linear-gradient(180deg,rgba(250,204,21,0.18),rgba(255,255,255,0.02))] p-6 sm:p-8">
                    <h3 class="text-2xl font-semibold text-white">Estados recomendados</h3>
                    <ul class="mt-5 space-y-2 text-sm text-white/75">
                        <li>• Registrado</li>
                        <li>• En cinta</li>
                        <li>• Cargado</li>
                        <li>• En tránsito</li>
                        <li>• Entregado</li>
                        <li>• Perdido</li>
                    </ul>
                </section>
            </aside>
        </div>
    </section>
@endsection