@extends('templates.app')

@section('title', 'Nueva aeronave | AeroControl')

@section('content')
    <section class="space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                @include('shared.page-title', [
                    'label' => 'Flota',
                    'title' => 'Añadir aeronave',
                ])
            </div>
            <a href="{{ route('admin.aeronaves') }}" class="outline-button inline-flex items-center rounded-2xl px-5 py-3 text-sm font-semibold">
                Volver a aeronaves
            </a>
        </div>

        <div class="glass-panel rounded-4xl p-6 sm:p-8">
            <form action="{{ route('admin.aeronaves.store') }}" method="POST" class="grid gap-5 sm:grid-cols-2">
                @csrf

                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm text-white/70" for="id_modelo">Modelo</label>
                    <select id="id_modelo" name="id_modelo" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                        <option value="">Selecciona un modelo</option>
                        @foreach($modelos as $modelo)
                            <option value="{{ $modelo->id_modelo }}">{{ $modelo->fabricante }} - {{ $modelo->nombre_comercial }} ({{ $modelo->autonomia_km }} km)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm text-white/70" for="matricula">Matrícula</label>
                    <input id="matricula" name="matricula" type="text" placeholder="EC-ABC" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div>
                    <label class="mb-2 block text-sm text-white/70" for="capacidad_max">Capacidad máxima</label>
                    <input id="capacidad_max" name="capacidad_max" type="number" min="1" placeholder="180" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                </div>

                <div class="sm:col-span-2 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.aeronaves') }}" class="outline-button inline-flex items-center rounded-2xl px-5 py-3 text-sm font-semibold">Cancelar</a>
                    <button type="submit" class="primary-button inline-flex items-center rounded-2xl px-5 py-3 text-sm font-semibold text-white">Guardar aeronave</button>
                </div>
            </form>
        </div>
    </section>
@endsection
