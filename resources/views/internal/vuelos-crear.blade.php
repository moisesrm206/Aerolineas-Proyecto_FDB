@extends('templates.app')

@section('title', 'Nuevo vuelo | Admin')

@section('content')
    <section class="space-y-8">
        @section('hero')
            @include('shared.page-hero', [
                'label' => 'Administración',
                'title' => 'Crear vuelo',
                'subtitle' => 'Define ruta, aeronave y horarios para un nuevo vuelo.',
            ])
        @endsection
        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr] lg:items-end">
            <div>
                @include('shared.page-title', [
                    'label' => 'Administración',
                    'title' => 'Crear vuelo',
                    'subtitle' => 'Registra una nueva salida, asigna la ruta y vincula la aeronave desde un formulario más claro y directo.',
                ])
            </div>

            <div class="glass-panel rounded-4xl p-5 sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-300">Qué se guarda</p>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm text-white/55">Ruta</p>
                        <p class="mt-1 text-lg font-semibold text-white">Origen + destino</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm text-white/55">Aeronave</p>
                        <p class="mt-1 text-lg font-semibold text-white">Matrícula activa</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm text-white/55">Horario</p>
                        <p class="mt-1 text-lg font-semibold text-white">Salida y llegada</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm text-white/55">Estado</p>
                        <p class="mt-1 text-lg font-semibold text-white">Programado</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-panel rounded-4xl p-6 sm:p-8">
            <form action="{{ route('admin.vuelos.store') }}" method="POST" class="grid gap-6">
                @csrf

                @if($errors->has('general'))
                    <div class="rounded-3xl border border-rose-300/20 bg-rose-300/10 px-5 py-4 text-sm font-medium text-rose-100">
                        {{ $errors->first('general') }}
                    </div>
                @endif

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/80" for="id_ruta">Ruta</label>
                        <select id="id_ruta" name="id_ruta" class="w-full rounded-2xl border {{ $errors->has('id_ruta') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            <option value="">Selecciona una ruta</option>
                            @foreach($rutas as $r)
                                <option value="{{ $r->id_ruta }}">Ruta #{{ $r->id_ruta }} · {{ $r->id_aeropuerto_origen }} → {{ $r->id_aeropuerto_destino }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-white/45">Usa la ruta que ya esté creada en la base de datos.</p>
                        @error('id_ruta')
                            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/80" for="id_aeronave">Aeronave</label>
                        <select id="id_aeronave" name="id_aeronave" class="w-full rounded-2xl border {{ $errors->has('id_aeronave') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            <option value="">Selecciona una aeronave</option>
                            @foreach($aeronaves as $a)
                                <option value="{{ $a->id_aeronave }}">{{ $a->matricula }} — {{ $a->nombre_modelo ?? $a->id_modelo }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-white/45">Solo puedes asignar aeronaves ya registradas.</p>
                        @error('id_aeronave')
                            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/80" for="salida_planificada">Salida planificada</label>
                        <input id="salida_planificada" name="salida_planificada" type="datetime-local" class="w-full rounded-2xl border {{ $errors->has('salida_planificada') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                        <p class="text-xs text-white/45">Formato local de fecha y hora.</p>
                        @error('salida_planificada')
                            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/80" for="llegada_planificada">Llegada planificada</label>
                        <input id="llegada_planificada" name="llegada_planificada" type="datetime-local" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                        <p class="text-xs text-white/45">Debe ser posterior a la salida.</p>
                        @error('llegada_planificada')
                            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 lg:col-span-2">
                        <label class="block text-sm font-medium text-white/80" for="estado">Estado</label>
                        <select id="estado" name="estado" class="w-full rounded-2xl border {{ $errors->has('estado') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            <option value="programado">programado</option>
                            <option value="en_vuelo">en_vuelo</option>
                            <option value="aterrizado">aterrizado</option>
                        </select>
                        <p class="text-xs text-white/45">Para vuelos nuevos conviene dejarlo en programado.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-white/10 pt-4">
                    <a href="{{ route('admin.vuelos') }}" class="outline-button inline-flex items-center rounded-2xl px-5 py-3 text-sm font-semibold">Cancelar</a>
                    <button type="submit" class="primary-button inline-flex items-center rounded-2xl px-6 py-3 text-sm font-semibold text-white">Crear vuelo</button>
                </div>
            </form>
        </div>
    </section>
@endsection
