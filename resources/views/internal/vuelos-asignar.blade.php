@extends('templates.app')

@section('title', 'Asignar tripulación | Admin')

@section('content')
    <section class="space-y-8">
        @section('hero')
            @include('shared.page-hero', [
                'label' => 'Operación',
                'title' => 'Asignar tripulación',
                'subtitle' => 'Asigna personal a vuelos y gestiona roles operativos.',
            ])
        @endsection
        <div>
            @include('shared.page-title', [
                'label' => 'Asignación',
                'title' => 'Asignar tripulación al vuelo ' . ($vuelo->id_vuelo ?? ''),
            ])
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_0.4fr]">
            <div class="glass-panel rounded-4xl p-6">
                <form id="asignarForm" action="{{ route('admin.vuelos.asignar', $vuelo->id_vuelo) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        @include('shared.typeahead-search', [
                            'searchId' => 'trip-search',
                            'resultsId' => 'trip-results',
                            'label' => 'Buscar tripulante',
                            'placeholder' => 'Escribe nombre o licencia...',
                        ])

                        <div>
                            <p class="mb-2 text-sm font-medium text-white/80">Asignaciones</p>
                            <div id="assignments" class="space-y-3">
                                {{-- Aquí se añadirán dinámicamente las filas con selects de rol --}}
                                @foreach($vuelo->tripulacionVuelo as $asig)
                                    <div class="grid gap-3 grid-cols-[1fr_260px] items-center assignment-row" data-id="{{ $asig->id_tripulacion }}">
                                        <div>
                                            <p class="text-sm text-white/60">{{ $asig->tripulacion?->usuario?->nombre ?? 'N/D' }}</p>
                                            <p class="mt-1 text-sm text-white/55">Licencia: {{ $asig->tripulacion?->num_licencia ?? 'N/D' }}</p>
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-white/80">Rol</label>
                                            <div class="flex gap-2 items-center">
                                                <select name="tripulacion[{{ $asig->id_tripulacion }}]" class="w-full rounded-2xl border border-white/10 bg-white/5 py-3 px-4 text-white">
                                                    <option value="">-- no asignar --</option>
                                                    @foreach($roles as $r)
                                                        <option value="{{ $r->id_rol }}" {{ $asig->id_rol == $r->id_rol ? 'selected' : '' }}>{{ $r->nombre_rol }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="ml-2 outline-button remove-assignment px-3 py-2" data-id="{{ $asig->id_tripulacion }}">Eliminar</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <button type="submit" class="primary-button rounded-2xl px-5 py-3">Guardar asignaciones</button>
                        <a href="{{ route('admin.vuelos') }}" class="outline-button rounded-2xl px-5 py-3">Cancelar</a>
                    </div>
                </form>
            </div>

            <aside class="space-y-4">
                <div class="glass-panel rounded-4xl p-5">
                    <p class="section-label">Resumen</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">Tripulación actual</h3>
                    <div class="mt-3 text-sm text-white/70">
                        @if($vuelo->tripulacionVuelo->isEmpty())
                            <p>No hay asignaciones para este vuelo.</p>
                        @else
                            <ul class="space-y-2">
                                @foreach($vuelo->tripulacionVuelo as $asig)
                                    <li class="flex items-center justify-between">
                                        <span>{{ $asig->tripulacion?->usuario?->nombre ?? 'N/D' }}</span>
                                        <span class="text-white/55">{{ $asig->rol?->nombre_rol ?? '' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </section>
    @push('scripts')
    <script>
        (() => {
            const boot = () => {
            const tripulaciones = @json($tripulaciones->map(fn($t)=>[ 'id' => $t->id_tripulacion, 'nombre' => $t->usuario?->nombre ?? '', 'licencia' => $t->num_licencia ?? '' ]));
            const roles = @json($roles->map(fn($r)=>[ 'id' => $r->id_rol, 'nombre' => $r->nombre_rol ]));

            const assignments = document.getElementById('assignments');

            if (!assignments || !window.initTypeaheadPicker) {
                return;
            }

            function addAssignment(item) {
                // evitar duplicados
                if (assignments.querySelector(`[data-id="${item.id}"]`)) return;

                const row = document.createElement('div');
                row.className = 'grid gap-3 grid-cols-[1fr_260px] items-center assignment-row';
                row.setAttribute('data-id', item.id);

                const left = document.createElement('div');
                left.innerHTML = `<p class="text-sm text-white/60">${item.nombre || 'Sin nombre'}</p><p class="mt-1 text-sm text-white/55">Licencia: ${item.licencia || 'N/D'}</p>`;

                const right = document.createElement('div');
                let options = `<option value="">-- no asignar --</option>`;
                for (const r of roles) {
                    options += `<option value="${r.id}">${r.nombre}</option>`;
                }
                right.innerHTML = `<label class="mb-2 block text-sm font-medium text-white/80">Rol</label><div class="flex gap-2 items-center"><select name="tripulacion[${item.id}]" class="w-full rounded-2xl border border-white/10 bg-white/5 py-3 px-4 text-white">${options}</select><button type="button" class="ml-2 outline-button remove-assignment px-3 py-2" data-id="${item.id}">Eliminar</button></div>`;

                row.appendChild(left);
                row.appendChild(right);
                assignments.appendChild(row);
                // efecto visual breve
                row.classList.add('ring-2', 'ring-cyan-300/30');
                setTimeout(() => row.classList.remove('ring-2', 'ring-cyan-300/30'), 900);

                // attach remove handler
                const removeBtn = row.querySelector('.remove-assignment');
                if (removeBtn) removeBtn.addEventListener('click', () => row.remove());
            }

            window.initTypeaheadPicker({
                inputSelector: '#trip-search',
                resultsSelector: '#trip-results',
                items: tripulaciones,
                minChars: 1,
                debounceMs: 180,
                filterItems(list, query) {
                    return list.filter((item) => {
                        const nombre = (item.nombre || '').toLowerCase();
                        const licencia = (item.licencia || '').toLowerCase();
                        return nombre.includes(query) || licencia.includes(query);
                    });
                },
                renderItem(item, index, activeIndex) {
                    const isActive = index === activeIndex;
                    const activeClass = isActive ? 'bg-cyan-300/15 ring-1 ring-cyan-300/40' : 'hover:bg-white/5';

                    return `<div class="p-2 rounded cursor-pointer transition ${activeClass}" data-index="${index}" role="option" aria-selected="${isActive ? 'true' : 'false'}">
                                <div class="text-sm ${isActive ? 'text-cyan-100' : ''}">${item.nombre || 'Sin nombre'}</div>
                                <div class="text-xs text-white/55">Licencia: ${item.licencia || 'N/D'}</div>
                            </div>`;
                },
                onSelect(item, helpers) {
                    addAssignment(item);
                    helpers.clear();
                },
            });

            // delegate remove buttons for existing rows
            assignments.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-assignment')) {
                    const row = e.target.closest('.assignment-row');
                    if (row) row.remove();
                }
            });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', boot, { once: true });
                return;
            }

            boot();
        })();
    </script>
    @endpush
@endsection
