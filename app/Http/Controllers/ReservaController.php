<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Support\Carbon;

class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::query()
            ->with([
                'pasajero',
                'vuelo.ruta.aeropuertoOrigen',
                'vuelo.ruta.aeropuertoDestino',
                'vuelo.aeronave.modelo',
                'boletos',
                'pago',
            ])
            ->orderByDesc('fecha_creacion')
            ->get()
            ->map(function (Reserva $reserva) {
                $vuelo = $reserva->vuelo;
                $ruta = $vuelo?->ruta;
                $origen = $ruta?->aeropuertoOrigen;
                $destino = $ruta?->aeropuertoDestino;
                $boletos = $reserva->boletos;
                $boleto = $boletos->first();
                $pago = $reserva->pago;
                $fechaCreacion = $reserva->fecha_creacion ? Carbon::parse($reserva->fecha_creacion) : null;
                $salida = $vuelo?->salida_planificada ? Carbon::parse($vuelo->salida_planificada) : null;

                $estadoNormalizado = mb_strtolower((string) ($reserva->estado ?? ''));
                $badge = match (true) {
                    str_contains($estadoNormalizado, 'cancel') => ['Cancelada', 'bg-rose-100 text-rose-700 ring-rose-200'],
                    str_contains($estadoNormalizado, 'check') => ['Check-in', 'bg-blue-100 text-blue-700 ring-blue-200'],
                    str_contains($estadoNormalizado, 'pend') => ['Pendiente', 'bg-amber-100 text-amber-700 ring-amber-200'],
                    str_contains($estadoNormalizado, 'paga') => ['Pagada', 'bg-emerald-100 text-emerald-700 ring-emerald-200'],
                    default => ['Confirmada', 'bg-amber-100 text-amber-700 ring-amber-200'],
                };

                $reserva->ruta_resumen = ($origen?->codigo_iata ?? 'N/D') . ' → ' . ($destino?->codigo_iata ?? 'N/D');
                $reserva->origen_codigo = $origen?->codigo_iata ?? 'N/D';
                $reserva->destino_codigo = $destino?->codigo_iata ?? 'N/D';
                $reserva->origen_ciudad = $origen?->ciudad ?? 'Sin dato';
                $reserva->destino_ciudad = $destino?->ciudad ?? 'Sin dato';
                $reserva->fecha_formateada = $salida?->translatedFormat('d \d\e F \d\e Y') ?? 'Sin fecha';
                $reserva->hora_salida = $salida?->format('H:i') ?? '--:--';
                $reserva->codigo_vuelo = 'AV' . str_pad((string) ($vuelo?->id_vuelo ?? $reserva->id_reserva), 3, '0', STR_PAD_LEFT);
                $reserva->asiento = $boleto?->numero_asiento ?? 'Pendiente';
                $reserva->clase = $boleto?->clase ?? 'Económica';
                $reserva->precio = $boleto?->precio ?? ($pago?->monto_total ?? 'Consultar');
                $reserva->moneda = 'MXN';
                $reserva->pago_estado = $pago?->metodo ?? 'Sin pago';
                $reserva->fecha_reserva = $fechaCreacion?->translatedFormat('d M Y') ?? 'Sin fecha';
                $reserva->reserva_label = 'Reserva #' . ($reserva->id_reserva ?? 'N/D');
                $reserva->badge_text = $badge[0];
                $reserva->badge_class = $badge[1];
                $reserva->boletos_total = $boletos->count();

                return $reserva;
            });

        $resumen = [
            'total' => $reservas->count(),
            'pagadas' => $reservas->filter(function ($reserva) {
                return str_contains(mb_strtolower((string) $reserva->estado), 'paga');
            })->count(),
            'checkin' => $reservas->filter(function ($reserva) {
                return str_contains(mb_strtolower((string) $reserva->estado), 'check');
            })->count(),
        ];

        return view('client.reservas', compact('reservas', 'resumen'));
    }
}