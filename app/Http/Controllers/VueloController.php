<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Vuelo;

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
            ->with([
                'ruta.aeropuertoOrigen',
                'ruta.aeropuertoDestino',
                'aeronave.modelo',
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

        $vuelos = $vuelosQuery
            ->orderBy('salida_planificada')
            ->get()
            ->map(function ($vuelo) {
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

        $resumen = [
            'total' => $vuelos->count(),
            'programados' => $vuelos->where('estado', 'programado')->count(),
            'en_vuelo' => $vuelos->where('estado', 'en_vuelo')->count(),
            'aterrizados' => $vuelos->where('estado', 'aterrizado')->count(),
        ];

        $vueloDestacado = $vuelos->first();

        return view('client.vuelos', compact('vuelos', 'resumen', 'filtros', 'vueloDestacado'));
    }
}