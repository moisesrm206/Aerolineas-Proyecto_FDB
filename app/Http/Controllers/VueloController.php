<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Vuelo;
use App\Models\Reserva;

class VueloController extends Controller
{
    public function index(Request $request)
    {
        $filtros = [
            'origen' => trim((string) $request->query('origen', '')),
            'destino' => trim((string) $request->query('destino', '')),
            'fecha' => trim((string) $request->query('fecha', '')),
        ];

        $vuelosQuery = Vuelo::query()
            ->select(['id_vuelo', 'id_ruta', 'id_aeronave', 'salida_planificada', 'llegada_planificada', 'estado'])
            ->with([
                'ruta:id_ruta,id_aeropuerto_origen,id_aeropuerto_destino,distancia_km',
                'ruta.aeropuertoOrigen:id_aeropuerto,codigo_iata,ciudad',
                'ruta.aeropuertoDestino:id_aeropuerto,codigo_iata,ciudad',
                'aeronave:id_aeronave,id_modelo,matricula,capacidad_max',
                'aeronave.modelo:id_modelo,fabricante,nombre_comercial,autonomia_km',
            ]);

        if ($filtros['origen'] !== '') {
            $vuelosQuery->whereHas('ruta.aeropuertoOrigen', function ($query) use ($filtros) {
                $query->whereRaw('UPPER(codigo_iata) like ?', ['%' . mb_strtoupper($filtros['origen']) . '%']);
            });
        }

        if ($filtros['destino'] !== '') {
            $vuelosQuery->whereHas('ruta.aeropuertoDestino', function ($query) use ($filtros) {
                $query->whereRaw('UPPER(codigo_iata) like ?', ['%' . mb_strtoupper($filtros['destino']) . '%']);
            });
        }

        if ($filtros['fecha'] !== '') {
            try {
                $fecha = Carbon::createFromFormat('d/m/Y', $filtros['fecha'])->toDateString();
                $vuelosQuery->whereDate('salida_planificada', $fecha);
            } catch (\Throwable $exception) {
                try {
                    $fecha = Carbon::parse($filtros['fecha'])->toDateString();
                    $vuelosQuery->whereDate('salida_planificada', $fecha);
                } catch (\Throwable $ignored) {
                    // Se ignora el filtro si no puede interpretarse.
                }
            }
        }

        // Si no es admin, limitar la vista a vuelos programados (modo pasajero/publico)
        if (!Auth::check() || Auth::user()->rol !== 'admin') {
            $vuelosQuery->where('estado', 'programado');
        }

        $countsQuery = clone $vuelosQuery;

        $paginator = $vuelosQuery
            ->orderBy('salida_planificada')
            ->paginate(20);

        $mapped = $paginator->getCollection()->map(function ($vuelo) {
            $salida = Carbon::parse($vuelo->salida_planificada);
            $llegada = Carbon::parse($vuelo->llegada_planificada);

            $origen = $vuelo->ruta?->aeropuertoOrigen;
            $destino = $vuelo->ruta?->aeropuertoDestino;
            $aeronave = $vuelo->aeronave;

            $vuelo->ruta = ($origen?->codigo_iata ?? 'N/D') . ' → ' . ($destino?->codigo_iata ?? 'N/D');
            $vuelo->origen = $origen?->codigo_iata ?? 'N/D';
            $vuelo->origen_ciudad = $origen?->ciudad ?? 'Sin dato';
            $vuelo->destino = $destino?->codigo_iata ?? 'N/D';
            $vuelo->destino_ciudad = $destino?->ciudad ?? 'Sin dato';
            $vuelo->salida = $salida->format('H:i');
            $vuelo->llegada = $llegada->format('H:i');
            $vuelo->fecha = $salida->translatedFormat('d M Y');
            $vuelo->aeronave_matricula = $aeronave?->matricula ?? 'N/D';
            $vuelo->capacidad_max = $aeronave?->capacidad_max ?? 'N/D';
            $vuelo->distancia_km = $vuelo->ruta?->distancia_km ?? 'N/D';

            return $vuelo;
        });

        // Replace paginator collection with mapped items
        $paginator->setCollection($mapped);

        $resumen = [
            'total' => $countsQuery->count(),
            'programados' => (clone $countsQuery)->where('estado', 'programado')->count(),
            'en_vuelo' => (clone $countsQuery)->where('estado', 'en_vuelo')->count(),
            'aterrizados' => (clone $countsQuery)->where('estado', 'aterrizado')->count(),
        ];

        $vueloDestacado = $paginator->first();

        return view('client.vuelos', ['vuelos' => $paginator, 'resumen' => $resumen, 'filtros' => $filtros, 'vueloDestacado' => $vueloDestacado]);
    }

    public function misVuelos(Request $request)
    {
        // Vuelos en los que ha volado el usuario (reservas)
        abort_unless(Auth::check(), 403);

        $user = Auth::user();
        $pasajero = $user->pasajero;

        if (!$pasajero) {
            return view('client.mis_vuelos', ['vuelos' => collect(), 'resumen' => ['total' => 0]]);
        }

        $reservasQuery = Reserva::query()
            ->where('id_pasajero', $pasajero->id_pasajero)
            ->with(['vuelo', 'vuelo.ruta', 'vuelo.aeronave']);

        $paginator = $reservasQuery->orderBy('fecha_creacion', 'desc')->paginate(20);

        $mapped = $paginator->getCollection()->map(function ($reserva) {
            $vuelo = $reserva->vuelo;
            if (!$vuelo) return null;

            $salida = $vuelo->salida_planificada ? \Illuminate\Support\Carbon::parse($vuelo->salida_planificada) : null;

            $origen = $vuelo->ruta?->aeropuertoOrigen;
            $destino = $vuelo->ruta?->aeropuertoDestino;

            return (object) [
                'id_vuelo' => $vuelo->id_vuelo,
                'reserva_id' => $reserva->id_reserva,
                'ruta' => ($origen?->codigo_iata ?? 'N/D') . ' → ' . ($destino?->codigo_iata ?? 'N/D'),
                'salida' => $salida?->format('H:i') ?? '--:--',
                'fecha' => $salida?->translatedFormat('d M Y') ?? '',
                'aeronave' => $vuelo->aeronave?->matricula ?? 'N/D',
                'estado' => $vuelo->estado,
            ];
        })->filter();

        $paginator->setCollection($mapped);

        return view('client.mis_vuelos', ['vuelos' => $paginator, 'resumen' => ['total' => $paginator->total()]]);
    }
}