@extends('templates.app')

@section('title', 'Nuevo modelo de aeronave | AeroControl')

@section('content')
    <section class="space-y-8">
        @section('hero')
            @include('shared.page-hero', [
                'label' => 'Administración',
                'title' => 'Crear modelo de aeronave',
                'subtitle' => 'Añade nuevos modelos comerciales y sus especificaciones.',
            ])
        @endsection
        <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr] lg:items-end">
            <div>
                @include('shared.page-title', [
                    'label' => 'Catálogo',
                    'title' => 'Nuevo modelo de aeronave',
                    'subtitle' => 'Registra fabricantes, nombres comerciales y autonomía para reutilizarlos al crear aeronaves.',
                ])
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

        <div class="grid gap-6 xl:grid-cols-[1fr_0.7fr]">
            <div class="glass-panel rounded-4xl p-6 sm:p-8">
                <form action="{{ route('admin.modelos-aeronave.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="fabricante" class="mb-2 block text-sm font-medium text-white/80">Fabricante</label>
                            <input id="fabricante" name="fabricante" type="text" value="{{ old('fabricante') }}" placeholder="Ej: Airbus" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            @error('fabricante')
                                <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nombre_comercial" class="mb-2 block text-sm font-medium text-white/80">Nombre comercial</label>
                            <input id="nombre_comercial" name="nombre_comercial" type="text" value="{{ old('nombre_comercial') }}" placeholder="Ej: A320neo" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            @error('nombre_comercial')
                                <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-[1fr_auto] md:items-end">
                        <div>
                            <label for="autonomia_km" class="mb-2 block text-sm font-medium text-white/80">Autonomía (km)</label>
                            <input id="autonomia_km" name="autonomia_km" type="number" min="1" value="{{ old('autonomia_km') }}" placeholder="Ej: 6100" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">
                            @error('autonomia_km')
                                <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="primary-button inline-flex items-center justify-center rounded-2xl px-6 py-3 text-sm font-semibold text-white transition duration-300">
                            Guardar modelo
                        </button>
                    </div>
                </form>
            </div>

            <aside class="space-y-4">
                <div class="glass-panel rounded-4xl p-5">
                    <p class="section-label">Consejo</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">Usa nombres claros</h3>
                    <p class="mt-3 text-sm leading-7 text-white/70">El nombre comercial aparecerá luego al elegir aeronaves, así que conviene mantener un formato consistente para evitar confusiones.</p>
                </div>

                <div class="glass-panel rounded-4xl p-5">
                    <p class="section-label">Modelos existentes</p>
                    <div class="mt-4 space-y-3 text-sm text-white/75">
                        @forelse($modelos as $modelo)
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="font-semibold text-white">{{ $modelo->nombre_comercial }}</p>
                                <p class="mt-1 text-white/60">{{ $modelo->fabricante }} · {{ $modelo->autonomia_km }} km</p>
                            </div>
                        @empty
                            <p>No hay modelos registrados todavía.</p>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $modelos->links() }}
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection