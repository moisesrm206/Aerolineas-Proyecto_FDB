<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Pasajero;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function store(Request $request)
    {
        // Usuario debe estar autenticado
        abort_unless(Auth::check(), 403);

        $data = $request->validate([
            'id_vuelo' => ['required', 'integer', 'exists:vuelo,id_vuelo'],
        ]);

        $user = Auth::user();
        $pasajero = $user->pasajero;

        if (!$pasajero) {
            return redirect()->route('cuenta.editar')->with('error', 'Completa tus datos de pasajero antes de reservar.');
        }

        $reserva = Reserva::create([
            'id_pasajero' => $pasajero->id_pasajero,
            'id_vuelo' => $data['id_vuelo'],
            'fecha_creacion' => Carbon::now()->toDateTimeString(),
            'estado' => 'pendiente',
        ]);

        return redirect()->route('reservas.lista')->with('status', 'Reserva creada correctamente.');
    }

    public function index()
    {
        $baseQuery = Reserva::query()
            ->select(['id_reserva', 'id_pasajero', 'id_vuelo', 'fecha_creacion', 'estado'])
            ->with([
                'pasajero:id_pasajero,id_user,pasaporte,nacionalidad',
                'pasajero.usuario:id_user,nombre',
                'vuelo:id_vuelo,id_ruta,id_aeronave,salida_planificada,llegada_planificada,estado',
                'vuelo.ruta:id_ruta,id_aeropuerto_origen,id_aeropuerto_destino,distancia_km',
                'vuelo.ruta.aeropuertoOrigen:id_aeropuerto,codigo_iata,ciudad',
                'vuelo.ruta.aeropuertoDestino:id_aeropuerto,codigo_iata,ciudad',
                'vuelo.aeronave:id_aeronave,id_modelo,matricula,capacidad_max',
                'vuelo.aeronave.modelo:id_modelo,fabricante,nombre_comercial,autonomia_km',
                'boletos:id_boleto,id_reserva,numero_asiento,clase,precio',
                'pago:id_pago,id_reserva,monto_total,metodo,fecha_hora',
            ])
            ->orderByDesc('fecha_creacion');

        $paginator = $baseQuery->paginate(20);

        $mapped = $paginator->getCollection()->map(function (Reserva $reserva) {
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

        $paginator->setCollection($mapped);

        // Totals computed from base query (no pagination)
        $total = Reserva::query()->count('id_reserva');
        $pagadas = Reserva::query()->where('estado', 'like', '%paga%')->count('id_reserva');
        $checkin = Reserva::query()->where('estado', 'like', '%check%')->count('id_reserva');

        $resumen = [
            'total' => $total,
            'pagadas' => $pagadas,
            'checkin' => $checkin,
        ];

        return view('client.reservas', ['reservas' => $paginator, 'resumen' => $resumen]);
    }
}