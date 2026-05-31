<?php

namespace App\Http\Controllers;

use App\Models\Aeronave;
use App\Models\Mantenimiento;
use App\Models\ModeloAeronave;
use App\Models\Pasajero;
use App\Models\RolTripulacion;
use App\Models\Tripulacion;
use App\Models\Vuelo;
use App\Models\Ruta;
use App\Models\TripulacionVuelo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function panel()
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        return view('internal.panel', ['user' => Auth::user()]);
    }

    public function aeronaves()
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $baseQuery = Aeronave::query()
            ->with([
                'modelo:id_modelo,fabricante,nombre_comercial,autonomia_km',
                'mantenimientos:id_mantenimiento,id_aeronave,fecha,tipo,responsable',
            ])
            ->withCount('mantenimientos')
            ->orderBy('matricula');

        $aeronaves = $baseQuery->paginate(6);

        $aeronaves->setCollection(
            $aeronaves->getCollection()->map(function (Aeronave $aeronave) {
                return $this->formatearAeronave($aeronave);
            })
        );

        $aeronavesTotales = (clone $baseQuery)
            ->get()
            ->map(function (Aeronave $aeronave) {
                return $this->formatearAeronave($aeronave);
            });

        $enMantenimiento = $aeronavesTotales->filter(function (Aeronave $aeronave) {
            return ($aeronave->estado_operativo ?? '') === 'En mantenimiento';
        })->count();

        $operativas = $aeronavesTotales->filter(function (Aeronave $aeronave) {
            return ($aeronave->estado_operativo ?? '') === 'Operativa';
        })->count();

        $resumen = [
            'total' => $aeronavesTotales->count(),
            'modelos' => $aeronavesTotales->pluck('id_modelo')->filter()->unique()->count(),
            'capacidad_total' => $aeronavesTotales->sum('capacidad_max'),
            'autonomia_promedio' => (int) round($aeronavesTotales->pluck('autonomia_km')->filter()->avg() ?? 0),
            'mantenimientos' => $aeronavesTotales->sum('mantenimientos_count'),
            'en_mantenimiento' => $enMantenimiento,
            'operativas' => $operativas,
        ];

        return view('internal.aeronaves', [
            'aeronaves' => $aeronaves,
            'resumen' => $resumen,
            'actualizado' => Carbon::now()->translatedFormat('d/m/Y H:i'),
        ]);
    }

    public function createAeronave()
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        return view('internal.aeronaves-crear', [
            'modelos' => ModeloAeronave::query()
                ->select(['id_modelo', 'fabricante', 'nombre_comercial', 'autonomia_km'])
                ->orderBy('nombre_comercial')
                ->get(),
        ]);
    }

    public function storeAeronave(Request $request)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $data = $request->validate([
            'id_modelo' => ['required', 'integer', 'exists:modelo_aeronave,id_modelo'],
            'matricula' => ['required', 'string', 'max:30', 'unique:aeronave,matricula'],
            'capacidad_max' => ['required', 'integer', 'min:1'],
        ]);

        Aeronave::create($data);

        return redirect()->route('admin.aeronaves')->with('status', 'Aeronave creada correctamente.');
    }

    private function formatearAeronave(Aeronave $aeronave): Aeronave
    {
        $modelo = $aeronave->modelo;
        $ultimoMantenimiento = $aeronave->mantenimientos->sortByDesc('fecha')->first();
        $fechaUltimoMantenimiento = $ultimoMantenimiento?->fecha
            ? Carbon::parse($ultimoMantenimiento->fecha)
            : null;

        $diasDesdeUltimoMantenimiento = $fechaUltimoMantenimiento
            ? Carbon::now()->diffInDays($fechaUltimoMantenimiento)
            : null;

        $tipoMantenimiento = mb_strtolower(trim((string) ($ultimoMantenimiento?->tipo ?? '')));
        $esMantenimientoHoy = $tipoMantenimiento === 'inspeccion'
            && $fechaUltimoMantenimiento !== null
            && $fechaUltimoMantenimiento->isSameDay(Carbon::now());
        $estadoOperativo = $esMantenimientoHoy ? 'En mantenimiento' : 'Operativa';
        $estadoOperativoClase = $esMantenimientoHoy
            ? 'text-amber-200'
            : 'text-emerald-300';

        if ($esMantenimientoHoy) {
            $estadoMantenimiento = 'En mantenimiento';
            $estadoClase = 'border-amber-300/20 bg-amber-300/10 text-amber-200';
        } elseif ($fechaUltimoMantenimiento === null) {
            $estadoMantenimiento = 'Sin registro';
            $estadoClase = 'border-amber-300/20 bg-amber-300/10 text-amber-200';
        } elseif ($diasDesdeUltimoMantenimiento <= 180) {
            $estadoMantenimiento = 'Al día';
            $estadoClase = 'border-emerald-300/20 bg-emerald-300/10 text-emerald-200';
        } else {
            $estadoMantenimiento = 'Requiere revisión';
            $estadoClase = 'border-orange-300/20 bg-orange-300/10 text-orange-200';
        }

        $aeronave->fabricante = $modelo?->fabricante ?? 'Sin modelo';
        $aeronave->nombre_modelo = $modelo?->nombre_comercial ?? 'N/D';
        $aeronave->autonomia_km = $modelo?->autonomia_km;
        $aeronave->ultimo_mantenimiento = $fechaUltimoMantenimiento?->translatedFormat('d/m/Y') ?? 'Sin registro';
        $aeronave->ultimo_tipo_mantenimiento = $ultimoMantenimiento?->tipo ?? 'Sin dato';
        $aeronave->ultimo_responsable = $ultimoMantenimiento?->responsable ?? 'Sin dato';
        $aeronave->estado_mantenimiento = $estadoMantenimiento;
        $aeronave->estado_clase = $estadoClase;
        $aeronave->estado_operativo = $estadoOperativo;
        $aeronave->estado_operativo_clase = $estadoOperativoClase;
        $aeronave->dias_desde_ultimo_mantenimiento = $diasDesdeUltimoMantenimiento;

        return $aeronave;
    }

    public function editAeronave(int $aeronaveId)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $aeronave = Aeronave::query()
            ->with('modelo:id_modelo,fabricante,nombre_comercial,autonomia_km')
            ->findOrFail($aeronaveId);

        return view('internal.aeronaves-editar', [
            'aeronave' => $aeronave,
            'modelos' => ModeloAeronave::query()
                ->select(['id_modelo', 'fabricante', 'nombre_comercial', 'autonomia_km'])
                ->orderBy('nombre_comercial')
                ->get(),
        ]);
    }

    public function updateAeronave(Request $request, int $aeronaveId)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $aeronave = Aeronave::query()->findOrFail($aeronaveId);

        $data = $request->validate([
            'id_modelo' => ['required', 'integer', 'exists:modelo_aeronave,id_modelo'],
            'matricula' => ['required', 'string', 'max:30'],
            'capacidad_max' => ['required', 'integer', 'min:1'],
        ]);

        $aeronave->update($data);

        return redirect()
            ->route('admin.aeronaves.editar', $aeronaveId)
            ->with('status', 'Aeronave actualizada correctamente.');
    }

    public function marcarAeronaveMantenimiento(int $aeronaveId)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $aeronave = Aeronave::query()->findOrFail($aeronaveId);

        Mantenimiento::create([
            'id_aeronave' => $aeronave->id_aeronave,
            'fecha' => Carbon::now()->toDateString(),
            'tipo' => 'inspeccion',
            'responsable' => Auth::user()->nombre ?? 'Administrador',
        ]);

        return redirect()
            ->route('admin.aeronaves')
            ->with('status', 'La aeronave fue marcada en mantenimiento.');
    }

    public function createAccount()
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        return view('access.register', [
            'modo_admin' => true,
            'rol_tripulacion_options' => RolTripulacion::query()
                ->get(['id_rol', 'nombre_rol'])
                ->sortBy('nombre_rol')
                ->values(),
        ]);
    }

    // Vuelos: listado y gestión (modo admin)
    public function vuelos(Request $request)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $filtros = [
            'origen' => trim((string) $request->query('origen', '')),
            'destino' => trim((string) $request->query('destino', '')),
            'fecha' => trim((string) $request->query('fecha', '')),
        ];

        $query = Vuelo::query()
            ->with([
                'ruta:id_ruta,id_aeropuerto_origen,id_aeropuerto_destino,distancia_km',
                'ruta.aeropuertoOrigen:id_aeropuerto,codigo_iata,ciudad',
                'ruta.aeropuertoDestino:id_aeropuerto,codigo_iata,ciudad',
                'aeronave:id_aeronave,id_modelo,matricula,capacidad_max',
                'aeronave.modelo:id_modelo,fabricante,nombre_comercial,autonomia_km',
                'tripulacionVuelo.tripulacion',
                'tripulacionVuelo.rol',
            ]);

        if ($filtros['origen'] !== '') {
            $query->whereHas('ruta.aeropuertoOrigen', function ($q) use ($filtros) {
                $q->whereRaw('UPPER(codigo_iata) like ?', ['%' . mb_strtoupper($filtros['origen']) . '%']);
            });
        }

        if ($filtros['destino'] !== '') {
            $query->whereHas('ruta.aeropuertoDestino', function ($q) use ($filtros) {
                $q->whereRaw('UPPER(codigo_iata) like ?', ['%' . mb_strtoupper($filtros['destino']) . '%']);
            });
        }

        if ($filtros['fecha'] !== '') {
            try {
                $fecha = Carbon::createFromFormat('d/m/Y', $filtros['fecha'])->toDateString();
                $query->whereDate('salida_planificada', $fecha);
            } catch (\Throwable $exception) {
                try {
                    $fecha = Carbon::parse($filtros['fecha'])->toDateString();
                    $query->whereDate('salida_planificada', $fecha);
                } catch (\Throwable $ignored) {
                }
            }
        }

        $paginator = $query->orderBy('salida_planificada')->paginate(20);

        $paginator->setCollection($paginator->getCollection()->map(function ($vuelo) {
            $salida = Carbon::parse($vuelo->salida_planificada);
            $llegada = Carbon::parse($vuelo->llegada_planificada);

            $origen = $vuelo->ruta?->aeropuertoOrigen;
            $destino = $vuelo->ruta?->aeropuertoDestino;

            $vuelo->ruta_txt = ($origen?->codigo_iata ?? 'N/D') . ' → ' . ($destino?->codigo_iata ?? 'N/D');
            $vuelo->salida_txt = $salida->format('d/m/Y H:i');
            $vuelo->tripulacion = $vuelo->tripulacionVuelo->map(function ($asig) {
                return [
                    'id_tripulacion' => $asig->id_tripulacion,
                    'nombre' => $asig->tripulacion?->usuario?->nombre ?? 'N/D',
                    'rol' => $asig->rol?->nombre_rol ?? 'N/D',
                ];
            });

            return $vuelo;
        }));

        return view('internal.vuelos-admin', [
            'vuelos' => $paginator,
            'filtros' => $filtros,
        ]);
    }

    public function crearVuelo()
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        return view('internal.vuelos-crear', [
            'rutas' => Ruta::all()->sortBy('id_ruta')->values(),
            'aeronaves' => Aeronave::all()->sortBy('matricula')->values(),
        ]);
    }

    public function editVuelo(int $vueloId)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $vuelo = Vuelo::query()
            ->with(['ruta:id_ruta,id_aeropuerto_origen,id_aeropuerto_destino', 'aeronave:id_aeronave,id_modelo,matricula'])
            ->findOrFail($vueloId);

        return view('internal.vuelos-editar', [
            'vuelo' => $vuelo,
            'rutas' => Ruta::query()->select(['id_ruta', 'id_aeropuerto_origen', 'id_aeropuerto_destino'])->orderBy('id_ruta')->get(),
            'aeronaves' => Aeronave::query()->select(['id_aeronave', 'matricula', 'id_modelo'])->orderBy('matricula')->get(),
        ]);
    }

    public function updateVuelo(Request $request, int $vueloId)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $vuelo = Vuelo::query()->findOrFail($vueloId);

        $data = $request->validate([
            'id_ruta' => ['required', 'integer', 'exists:ruta,id_ruta'],
            'id_aeronave' => ['required', 'integer', 'exists:aeronave,id_aeronave'],
            'salida_planificada' => ['required', 'date'],
            'llegada_planificada' => ['required', 'date'],
            'estado' => ['required', 'in:programado,en_vuelo,aterrizado'],
        ]);

        $vuelo->update($data);

        return redirect()->route('admin.vuelos')->with('status', 'Vuelo actualizado correctamente.');
    }

    public function storeVuelo(Request $request)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $data = $request->validate([
            'id_ruta' => ['required', 'integer', 'exists:ruta,id_ruta'],
            'id_aeronave' => ['required', 'integer', 'exists:aeronave,id_aeronave'],
            'salida_planificada' => ['required', 'date'],
            'llegada_planificada' => ['required', 'date'],
            'estado' => ['required', 'in:programado,en_vuelo,aterrizado'],
        ]);

        $vuelo = Vuelo::create($data);

        return redirect('/admin/vuelos');
    }

    public function asignarTripulacion(Request $request, int $vueloId)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $vuelo = Vuelo::findOrFail($vueloId);

        $asignaciones = $request->input('tripulacion', []); // associative array [id_trip => id_rol]

        DB::transaction(function () use ($vueloId, $asignaciones) {
            TripulacionVuelo::query()->where('id_vuelo', '=', $vueloId, 'and')->delete();

            foreach ($asignaciones as $tripId => $rolId) {
                if (empty($rolId)) continue;
                TripulacionVuelo::create([
                    'id_vuelo' => $vueloId,
                    'id_tripulacion' => (int) $tripId,
                    'id_rol' => (int) $rolId,
                ]);
            }
        });

        return redirect('/admin/vuelos')->with('status', 'Tripulación asignada correctamente.');
    }

    public function asignarForm(int $vueloId)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $vuelo = Vuelo::with('tripulacionVuelo')->findOrFail($vueloId);

        $tripulaciones = Tripulacion::with('usuario:id_user,nombre')->get()->sortBy(fn ($tripulacion) => $tripulacion->usuario?->nombre ?? '')->values();
        $roles = RolTripulacion::all()->sortBy('nombre_rol')->values();

        return view('internal.vuelos-asignar', [
            'vuelo' => $vuelo,
            'tripulaciones' => $tripulaciones,
            'roles' => $roles,
        ]);
    }

    public function storeAccount(Request $request)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:200', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,telefono'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'rol' => ['required', 'in:admin,tripulacion,pasajero'],
            'license_number' => ['nullable', 'string', 'max:50', 'unique:tripulacion,num_licencia'],
            'crew_role_id' => ['required_if:rol,tripulacion', 'nullable', 'integer', 'exists:rol_tripulacion,id_rol'],
            'passport_number' => ['nullable', 'string', 'max:30', 'unique:pasajero,pasaporte'],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'nombre' => $data['name'],
                'email' => $data['email'],
                'telefono' => $data['phone'] ?? null,
                'contrasenna' => Hash::make($data['password']),
                'rol' => $data['rol'] ?? 'admin',
            ]);

            if (($data['rol'] ?? '') === 'tripulacion') {
                Tripulacion::create([
                    'id_user' => $user->id_user,
                    'id_rol' => $data['crew_role_id'] ?? 1,
                    'num_licencia' => $data['license_number'] ?? '',
                ]);
            }

            if (($data['rol'] ?? '') === 'pasajero') {
                Pasajero::create([
                    'id_user' => $user->id_user,
                    'pasaporte' => $data['passport_number'] ?? 'NO-DISPONIBLE',
                    'nacionalidad' => 'Desconocida',
                ]);
            }

            return $user;
        });

        return redirect()->route('admin.panel');
    }
}
