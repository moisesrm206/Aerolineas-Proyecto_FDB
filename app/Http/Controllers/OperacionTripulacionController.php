<?php

namespace App\Http\Controllers;

use App\Models\Vuelo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OperacionTripulacionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        abort_unless($user && in_array($user->rol, ['admin', 'tripulacion'], true), 403);

        $now = Carbon::now();

        $query = Vuelo::query()
            ->select(['id_vuelo', 'id_ruta', 'id_aeronave', 'salida_planificada', 'llegada_planificada', 'estado'])
            ->with([
                'ruta:id_ruta,id_aeropuerto_origen,id_aeropuerto_destino,distancia_km',
                'ruta.aeropuertoOrigen:id_aeropuerto,codigo_iata,ciudad',
                'ruta.aeropuertoDestino:id_aeropuerto,codigo_iata,ciudad',
                'aeronave:id_aeronave,id_modelo,matricula,capacidad_max',
                'aeronave.modelo:id_modelo,fabricante,nombre_comercial',
                'tripulacionVuelo.tripulacion:id_tripulacion,id_user,id_rol,num_licencia',
                'tripulacionVuelo.tripulacion.usuario:id_user,nombre',
                'tripulacionVuelo.rol:id_rol,nombre_rol',
            ]);

        if ($user->rol === 'tripulacion') {
            $tripulacionId = $user->tripulacion?->id_tripulacion;

            abort_unless($tripulacionId, 403);

            $query->whereHas('tripulacionVuelo', function ($relationQuery) use ($tripulacionId) {
                $relationQuery->where('id_tripulacion', $tripulacionId);
            });
        }

        $allFlights = $query->orderBy('salida_planificada')->get()->map(function (Vuelo $vuelo) {
            return $this->formatFlight($vuelo);
        });

        $upcomingFlights = $allFlights->filter(fn (object $vuelo) => Carbon::parse($vuelo->salida_planificada)->greaterThanOrEqualTo(Carbon::now()));
        $pastFlights = $allFlights->filter(fn (object $vuelo) => Carbon::parse($vuelo->salida_planificada)->lessThan(Carbon::now()));

        $summary = [
            'upcoming' => $upcomingFlights->count(),
            'past' => $pastFlights->count(),
            'assigned' => $allFlights->count(),
            'role' => $user->rol,
        ];

        return view('internal.operacion-tripulacion', [
            'user' => $user,
            'summary' => $summary,
            'upcomingFlights' => $upcomingFlights,
            'pastFlights' => $pastFlights,
            'nowLabel' => $now->translatedFormat('d M Y H:i'),
        ]);
    }

    private function formatFlight(Vuelo $vuelo): object
    {
        $salida = Carbon::parse($vuelo->salida_planificada);
        $llegada = Carbon::parse($vuelo->llegada_planificada);

        $origen = $vuelo->ruta?->aeropuertoOrigen;
        $destino = $vuelo->ruta?->aeropuertoDestino;
        $aeronave = $vuelo->aeronave;
        $assignments = $vuelo->tripulacionVuelo;
        $crewAssignments = $assignments->map(function ($assignment) {
            return (object) [
                'nombre' => $assignment->tripulacion?->usuario?->nombre ?? 'Sin asignar',
                'rol' => $assignment->rol?->nombre_rol ?? 'N/D',
            ];
        })->values();

        return (object) [
            'id_vuelo' => $vuelo->id_vuelo,
            'estado' => $vuelo->estado,
            'origen' => $origen?->codigo_iata ?? 'N/D',
            'origen_ciudad' => $origen?->ciudad ?? 'Sin dato',
            'destino' => $destino?->codigo_iata ?? 'N/D',
            'destino_ciudad' => $destino?->ciudad ?? 'Sin dato',
            'ruta' => ($origen?->codigo_iata ?? 'N/D') . ' → ' . ($destino?->codigo_iata ?? 'N/D'),
            'salida_formateada' => $salida->translatedFormat('d M Y'),
            'hora_salida' => $salida->format('H:i'),
            'hora_llegada' => $llegada->format('H:i'),
            'matricula' => $aeronave?->matricula ?? 'N/D',
            'capacidad' => $aeronave?->capacidad_max ?? 'N/D',
            'tripulacion_asignaciones' => $crewAssignments,
            'tiempo_relativo' => $salida->greaterThanOrEqualTo(Carbon::now()) ? 'Próximo' : 'Pasado',
        ];
    }
}
