@extends('templates.app')

@section('title', 'Editar vuelo | Admin')

@section('content')
    <section class="space-y-8">
        @section('hero')
            @include('shared.page-hero', [
                'label' => 'Administración',
                'title' => 'Editar vuelo',
                'subtitle' => 'Modifica parámetros, horarios o aeronave asignada.',
            ])
        @endsection
        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr] lg:items-end">
            <div>
                @include('shared.page-title', [
                    'label' => 'Administración',
                    'title' => 'Editar vuelo',
                    'subtitle' => 'Ajusta ruta, aeronave, horario y estado operativo del vuelo sin salir del flujo de administración.',
                ])
            </div>

            <div class="glass-panel rounded-4xl p-5 sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-300">Vuelo actual</p>
                <div class="mt-4 space-y-3">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                        <p class="text-sm text-white/55">Ruta</p>
                        <p class="mt-1 text-lg font-semibold text-white">{{ $vuelo->ruta?->id_aeropuerto_origen ?? 'N/D' }} → {{ $vuelo->ruta?->id_aeropuerto_destino ?? 'N/D' }}</p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-white/55">Aeronave</p>
                            <p class="mt-1 text-lg font-semibold text-white">{{ $vuelo->aeronave?->matricula ?? 'N/D' }}</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-white/55">Estado</p>
                            <p class="mt-1 text-lg font-semibold text-white">{{ ucfirst($vuelo->estado ?? 'programado') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-panel rounded-4xl p-6 sm:p-8">
            <form action="{{ route('admin.vuelos.actualizar', $vuelo->id_vuelo) }}" method="POST" class="grid gap-6">
                @csrf
                @method('PUT')

                @if($errors->has('general'))
                    <div class="rounded-3xl border border-rose-300/20 bg-rose-300/10 px-5 py-4 text-sm font-medium text-rose-100">
                        {{ $errors->first('general') }}
                    </div>
                @endif

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/80" for="id_ruta">Ruta</label>
                        <select id="id_ruta" name="id_ruta" class="w-full rounded-2xl border {{ $errors->has('id_ruta') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            @foreach($rutas as $ruta)
                                <option value="{{ $ruta->id_ruta }}" @selected($vuelo->id_ruta == $ruta->id_ruta)>
                                    Ruta #{{ $ruta->id_ruta }} · {{ $ruta->id_aeropuerto_origen }} → {{ $ruta->id_aeropuerto_destino }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_ruta')
                            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/80" for="id_aeronave">Aeronave</label>
                        <select id="id_aeronave" name="id_aeronave" class="w-full rounded-2xl border {{ $errors->has('id_aeronave') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            @foreach($aeronaves as $aeronave)
                                <option value="{{ $aeronave->id_aeronave }}" @selected($vuelo->id_aeronave == $aeronave->id_aeronave)>
                                    {{ $aeronave->matricula }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_aeronave')
                            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/80" for="salida_planificada">Salida planificada</label>
                        <input id="salida_planificada" name="salida_planificada" type="datetime-local" value="{{ optional($vuelo->salida_planificada)->format('Y-m-d\TH:i') }}" class="w-full rounded-2xl border {{ $errors->has('salida_planificada') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                        @error('salida_planificada')
                            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-white/80" for="llegada_planificada">Llegada planificada</label>
                        <input id="llegada_planificada" name="llegada_planificada" type="datetime-local" value="{{ optional($vuelo->llegada_planificada)->format('Y-m-d\TH:i') }}" class="w-full rounded-2xl border {{ $errors->has('llegada_planificada') ? 'border-rose-300' : 'border-white/10' }} bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                        @error('llegada_planificada')
                            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 lg:col-span-2">
                        <label class="block text-sm font-medium text-white/80" for="estado">Estado</label>
                        <select id="estado" name="estado" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            <option value="programado" @selected($vuelo->estado === 'programado')>programado</option>
                            <option value="en_vuelo" @selected($vuelo->estado === 'en_vuelo')>en_vuelo</option>
                            <option value="aterrizado" @selected($vuelo->estado === 'aterrizado')>aterrizado</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-white/10 pt-4">
                    <a href="{{ route('admin.vuelos') }}" class="outline-button inline-flex items-center rounded-2xl px-5 py-3 text-sm font-semibold">Cancelar</a>
                    <button type="submit" class="primary-button inline-flex items-center rounded-2xl px-6 py-3 text-sm font-semibold text-white">Guardar cambios</button>
                </div>
            </form>
        </div>
    </section>
@endsection