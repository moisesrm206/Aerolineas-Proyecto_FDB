@extends('templates.app')

@section('title', 'Asignar tripulación | Admin')

@section('content')
    <section>
        <p class="section-label">Asignación</p>
        <h1 class="mt-2 text-2xl font-bold text-white">Asignar tripulación al vuelo {{ $vuelo->id_vuelo }}</h1>

        <div class="glass-panel p-6 mt-4">
            <form action="{{ route('admin.vuelos.asignar', $vuelo->id_vuelo) }}" method="POST">
                @csrf
                <div class="space-y-3">
                    @foreach($tripulaciones as $t)
                        @php
                            $current = $vuelo->tripulacionVuelo->firstWhere('id_tripulacion', $t->id_tripulacion);
                        @endphp
                        <div class="grid grid-cols-[1fr_220px] gap-3 items-center">
                            <div class="text-white">{{ $t->nombre_completo }} ({{ $t->num_licencia ?? '' }})</div>
                            <select name="tripulacion[{{ $t->id_tripulacion }}]" class="w-full rounded">
                                <option value="">-- no asignar --</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id_rol }}" {{ $current && $current->id_rol == $r->id_rol ? 'selected' : '' }}>{{ $r->nombre_rol }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <button class="primary-button">Guardar asignaciones</button>
                </div>
            </form>
        </div>
    </section>
@endsection
