@extends('templates.app')

@section('title', 'Editar aeronave | AeroControl')

@section('content')
    <section class="space-y-8">
        <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr] lg:items-end">
            <div>
                <p class="section-label">Acceso administrativo</p>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl">Editar aeronave</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-white/70 sm:text-lg">
                    Actualiza la matrícula, la capacidad y el modelo asociado de esta aeronave.
                </p>
            </div>

            <div class="flex flex-wrap gap-3 lg:justify-end">
                <a href="{{ route('admin.aeronaves') }}" class="outline-button inline-flex items-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold transition duration-300">
                    <ion-icon name="arrow-back-sharp"></ion-icon>
                    Volver a aeronaves
                </a>
            </div>
        </div>

        @if(session('status'))
            <div class="rounded-3xl border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-medium text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[0.92fr_1.08fr]">
            <article class="glass-panel rounded-[1.75rem] p-6">
                <p class="section-label">Resumen</p>
                <h2 class="mt-3 text-3xl font-bold text-white">{{ $aeronave->modelo?->nombre_comercial ?? 'Modelo sin nombre' }}</h2>
                <div class="mt-6 space-y-4 text-sm text-white/75">
                    <p>Matrícula actual: {{ $aeronave->matricula }}</p>
                    <p>Capacidad actual: {{ $aeronave->capacidad_max }} pasajeros</p>
                    <p>Fabricante: {{ $aeronave->modelo?->fabricante ?? 'Sin dato' }}</p>
                    <p>Autonomía: {{ $aeronave->modelo?->autonomia_km ? number_format((float) $aeronave->modelo->autonomia_km, 0, ',', '.') . ' km' : 'Sin dato' }}</p>
                </div>
            </article>

            <form action="{{ route('admin.aeronaves.actualizar', $aeronave->id_aeronave) }}" method="POST" class="glass-panel rounded-[1.75rem] p-6">
                @csrf
                @method('PUT')

                <div class="grid gap-5">
                    <div>
                        <label class="mb-2 block text-sm text-white/70">Modelo</label>
                        <select name="id_modelo" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            @foreach ($modelos as $modelo)
                                <option value="{{ $modelo->id_modelo }}" @selected(old('id_modelo', $aeronave->id_modelo) == $modelo->id_modelo)>
                                    {{ $modelo->fabricante }} · {{ $modelo->nombre_comercial }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_modelo')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm text-white/70">Matrícula</label>
                        <input type="text" name="matricula" value="{{ old('matricula', $aeronave->matricula) }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                        @error('matricula')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm text-white/70">Capacidad máxima</label>
                        <input type="number" min="1" name="capacidad_max" value="{{ old('capacidad_max', $aeronave->capacidad_max) }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                        @error('capacidad_max')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="primary-button rounded-2xl px-6 py-3 text-sm font-semibold text-white transition duration-300">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection