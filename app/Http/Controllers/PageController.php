<?php

namespace App\Http\Controllers;

use App\Models\Equipaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class PageController extends Controller
{
    public function home()
    {
        if (Auth::check()) {
            return redirect()->route('panel.principal');
        }

        return view('public.home');
    }

    public function equipaje(Request $request)
    {
        $pasajero = Auth::user()?->pasajero;
        $filtroVuelos = $request->query('filtro_vuelos', 'actuales');

        if (!in_array($filtroVuelos, ['actuales', 'anteriores', 'todos'], true)) {
            $filtroVuelos = 'actuales';
        }

        $now = Carbon::now();
        $equipajeQuery = $pasajero
            ? $pasajero->equipajes()->with(['boleto.reserva.vuelo.ruta.aeropuertoOrigen', 'boleto.reserva.vuelo.ruta.aeropuertoDestino'])
            : Equipaje::query()->where('id_equipaje', '=', -1);

        $equipajesTotales = $pasajero
            ? $pasajero->equipajes()->with(['boleto.reserva.vuelo.ruta.aeropuertoOrigen', 'boleto.reserva.vuelo.ruta.aeropuertoDestino'])->get()
            : collect();

        if ($filtroVuelos === 'actuales') {
            $equipajeQuery->whereHas('boleto.reserva.vuelo', function ($query) use ($now) {
                $query->where(function ($subQuery) use ($now) {
                    $subQuery->whereNull('llegada_planificada')
                        ->orWhere('llegada_planificada', '>=', $now);
                });
            });
        } elseif ($filtroVuelos === 'anteriores') {
            $equipajeQuery->whereHas('boleto.reserva.vuelo', function ($query) use ($now) {
                $query->whereNotNull('llegada_planificada')
                    ->where('llegada_planificada', '<', $now);
            });
        }

        $equipajes = $equipajeQuery
            ->orderByDesc('id_equipaje')
            ->get();

        $resumen = [
            'total' => $equipajesTotales->count(),
            'mano' => $equipajesTotales->where('tipo_equipaje', 'mano')->count(),
            'bodega' => $equipajesTotales->where('tipo_equipaje', 'bodega')->count(),
            'registrado' => $equipajesTotales->where('estado', 'registrado')->count(),
            'en_cinta' => $equipajesTotales->where('estado', 'en_cinta')->count(),
            'cargado' => $equipajesTotales->where('estado', 'cargado')->count(),
            'en_transito' => $equipajesTotales->where('estado', 'en_transito')->count(),
            'entregado' => $equipajesTotales->where('estado', 'entregado')->count(),
            'perdido' => $equipajesTotales->where('estado', 'perdido')->count(),
            'actuales' => $equipajesTotales->filter(function ($equipaje) use ($now) {
                $llegada = $equipaje->boleto?->reserva?->vuelo?->llegada_planificada;

                return $llegada === null || Carbon::parse($llegada)->greaterThanOrEqualTo($now);
            })->count(),
            'anteriores' => $equipajesTotales->filter(function ($equipaje) use ($now) {
                $llegada = $equipaje->boleto?->reserva?->vuelo?->llegada_planificada;

                return $llegada !== null && Carbon::parse($llegada)->lessThan($now);
            })->count(),
        ];

        return view('client.equipaje', compact('equipajes', 'resumen', 'pasajero', 'filtroVuelos'));
    }
}
