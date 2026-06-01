@extends('templates.app')

@section('content')
    @include('shared.page-hero', [
        'title' => 'Check-in Mostrador',
        'subtitle' => 'Realizar check-in manual para pasajeros',
        'icon' => 'fas fa-qrcode'
    ])
<div class="min-h-screen bg-linear-to-br from-slate-950 via-slate-900 to-slate-950">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg border border-rose-500/30 bg-rose-500/10">
                <p class="text-rose-200 font-semibold">Error:</p>
                <ul class="text-rose-300 text-sm mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="mb-6 p-4 rounded-lg border border-emerald-500/30 bg-emerald-500/10">
                <p class="text-emerald-200">{{ session('status') }}</p>
            </div>
        @endif

        <!-- Formulario de búsqueda -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-3 rounded-xl border border-slate-700/50 bg-gradient-to-br from-slate-800/50 to-slate-900/50 p-6 backdrop-blur-xl">
                <h2 class="text-lg font-semibold text-slate-100 mb-4">Buscar Reserva</h2>
                
                <form method="GET" action="{{ route('admin.check-in.form') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                ID Reserva
                            </label>
                            <input 
                                type="text" 
                                name="id_reserva" 
                                value="{{ request('id_reserva') }}"
                                placeholder="Ej: 123"
                                class="w-full px-4 py-2 rounded-lg border border-slate-600 bg-slate-700/50 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:outline-none transition"
                            />
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Pasaporte
                            </label>
                            <input 
                                type="text" 
                                name="pasaporte" 
                                value="{{ request('pasaporte') }}"
                                placeholder="Ej: ABC123456"
                                class="w-full px-4 py-2 rounded-lg border border-slate-600 bg-slate-700/50 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:outline-none transition"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Estado
                            </label>
                            <select name="estado" class="w-full px-4 py-2 rounded-lg border border-slate-600 bg-slate-700/50 text-slate-100 focus:border-cyan-500 focus:outline-none transition">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ request('estado') === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="check-in" {{ request('estado') === 'check-in' ? 'selected' : '' }}>Check-in</option>
                                <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                    </div>

                    <button 
                        type="submit"
                        class="w-full md:w-auto px-6 py-2 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold transition transform hover:scale-105"
                    >
                        <i class="fas fa-search mr-2"></i> Buscar
                    </button>
                </form>
            </div>
        </div>

        <!-- Listado de resultados -->
        @php
            $reservas = [];
            if (request('id_reserva') || request('pasaporte') || request('estado')) {
                $query = \App\Models\Reserva::query()
                    ->with([
                        'pasajero.usuario',
                        'vuelo.ruta.aeropuertoOrigen',
                        'vuelo.ruta.aeropuertoDestino',
                        'boletos',
                        'pago'
                    ]);

                if (request('id_reserva')) {
                    $query->where('id_reserva', request('id_reserva'));
                }
                if (request('pasaporte')) {
                    $query->whereHas('pasajero', function ($q) {
                        $q->where('pasaporte', 'like', '%' . request('pasaporte') . '%');
                    });
                }
                if (request('estado')) {
                    $query->where('estado', request('estado'));
                }

                $reservas = $query->orderByDesc('fecha_creacion')->get();
            }
        @endphp

        @if (count($reservas) > 0)
            <div class="grid grid-cols-1 gap-4">
                @foreach ($reservas as $reserva)
                    @php
                        $vuelo = $reserva->vuelo;
                        $ruta = $vuelo?->ruta;
                        $origen = $ruta?->aeropuertoOrigen;
                        $destino = $ruta?->aeropuertoDestino;
                        $pasajero = $reserva->pasajero;
                        $usuario = $pasajero?->usuario;
                        $boleto = $reserva->boletos->first();
                        $salida = $vuelo?->salida_planificada ? \Carbon\Carbon::parse($vuelo->salida_planificada) : null;
                        
                        $estadoClase = match ($reserva->estado) {
                            'check-in' => 'border-blue-500/30 bg-blue-500/10 text-blue-200',
                            'confirmada' => 'border-emerald-500/30 bg-emerald-500/10 text-emerald-200',
                            'pendiente' => 'border-amber-500/30 bg-amber-500/10 text-amber-200',
                            'cancelada' => 'border-rose-500/30 bg-rose-500/10 text-rose-200',
                            default => 'border-slate-500/30 bg-slate-500/10 text-slate-200'
                        };
                    @endphp
                    
                    <div class="rounded-lg border {{ $estadoClase }} p-4 backdrop-blur-sm">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-slate-100">
                                        Reserva #{{ $reserva->id_reserva }}
                                    </h3>
                                    <span class="text-xs px-2 py-1 rounded-full bg-slate-700/50 text-slate-300">
                                        {{ ucfirst($reserva->estado) }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                    <div>
                                        <p class="text-slate-400">Pasajero</p>
                                        <p class="text-slate-100 font-medium">{{ $usuario?->nombre ?? 'N/D' }}</p>
                                        <p class="text-slate-500 text-xs">{{ $pasajero?->pasaporte ?? 'N/D' }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-slate-400">Ruta</p>
                                        <p class="text-slate-100 font-medium">
                                            {{ $origen?->codigo_iata ?? 'N/D' }} → {{ $destino?->codigo_iata ?? 'N/D' }}
                                        </p>
                                        <p class="text-slate-500 text-xs">{{ $salida?->format('d/m/Y H:i') ?? 'N/D' }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-slate-400">Asiento</p>
                                        <p class="text-slate-100 font-medium">{{ $boleto?->numero_asiento ?? 'Pendiente' }}</p>
                                        <p class="text-slate-500 text-xs">{{ ucfirst($boleto?->clase ?? 'economica') }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-slate-400">Precio</p>
                                        <p class="text-slate-100 font-medium">${{ number_format($boleto?->precio ?? 0, 2) }}</p>
                                        <p class="text-slate-500 text-xs">MXN</p>
                                    </div>
                                </div>
                            </div>

                            @if ($reserva->estado !== 'check-in' && $reserva->estado !== 'cancelada')
                                <form 
                                    action="{{ route('admin.check-in.store', $reserva->id_reserva) }}" 
                                    method="POST"
                                    class="flex-shrink-0"
                                >
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="px-4 py-2 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold transition transform hover:scale-105 whitespace-nowrap"
                                    >
                                        <i class="fas fa-check-circle mr-2"></i> Hacer Check-in
                                    </button>
                                </form>
                            @else
                                <div class="flex-shrink-0">
                                    <button 
                                        disabled
                                        class="px-4 py-2 rounded-lg bg-slate-700/50 text-slate-400 font-semibold cursor-not-allowed whitespace-nowrap"
                                    >
                                        <i class="fas fa-ban mr-2"></i> No disponible
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif (request()->query())
            <div class="rounded-lg border border-amber-500/30 bg-amber-500/10 p-6 text-center">
                <p class="text-amber-200">
                    <i class="fas fa-search mr-2"></i>
                    No se encontraron reservas con los criterios especificados.
                </p>
            </div>
        @else
            <div class="rounded-lg border border-slate-700/50 bg-slate-800/30 p-6 text-center">
                <p class="text-slate-300">
                    <i class="fas fa-info-circle mr-2"></i>
                    Usa el formulario de búsqueda para encontrar reservas.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
