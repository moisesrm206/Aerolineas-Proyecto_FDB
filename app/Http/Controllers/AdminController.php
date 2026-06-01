<?php

namespace App\Http\Controllers;

use App\Models\Aeronave;
use App\Models\Aeropuerto;
use App\Models\Boleto;
use App\Models\Equipaje;
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
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
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

        $filtro = trim((string) request()->query('q', ''));

        $baseQuery = Aeronave::query()
            ->with([
                'modelo:id_modelo,fabricante,nombre_comercial,autonomia_km',
                'mantenimientos:id_mantenimiento,id_aeronave,fecha,tipo,responsable',
            ])
            ->withCount('mantenimientos')
            ->when($filtro !== '', function ($query) use ($filtro) {
                $termino = mb_strtolower($filtro);

                $query->where(function ($subQuery) use ($termino) {
                    $subQuery->whereRaw('LOWER(matricula) like ?', ['%' . $termino . '%'])
                        ->orWhereHas('modelo', function ($modeloQuery) use ($termino) {
                            $modeloQuery->whereRaw('LOWER(nombre_comercial) like ?', ['%' . $termino . '%'])
                                ->orWhereRaw('LOWER(fabricante) like ?', ['%' . $termino . '%']);
                        });
                });
            })
            ->orderBy('matricula');

        $aeronavesBusqueda = (clone $baseQuery)->get()->map(function (Aeronave $aeronave) {
            $modelo = $aeronave->modelo;

            return [
                'id' => $aeronave->id_aeronave,
                'nombre' => $aeronave->matricula,
                'licencia' => trim(($modelo?->nombre_comercial ?? 'Sin modelo') . ' · ' . ($modelo?->fabricante ?? 'Sin fabricante')),
                'texto' => trim($aeronave->matricula . ' ' . ($modelo?->nombre_comercial ?? '') . ' ' . ($modelo?->fabricante ?? '')),
            ];
        });

        $aeronaves = $baseQuery->paginate(6)->withQueryString();

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
            'filtro' => $filtro,
            'aeronavesBusqueda' => $aeronavesBusqueda,
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

    public function createModeloAeronave()
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        return view('internal.modelos-aeronave-crear', [
            'modelos' => ModeloAeronave::query()
                ->select(['id_modelo', 'fabricante', 'nombre_comercial', 'autonomia_km'])
                ->orderBy('nombre_comercial')
                ->paginate(8)
                ->withQueryString(),
        ]);
    }

    public function storeModeloAeronave(Request $request)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $data = $request->validate([
            'fabricante' => ['required', 'string', 'max:120'],
            'nombre_comercial' => ['required', 'string', 'max:120'],
            'autonomia_km' => ['required', 'integer', 'min:1'],
        ]);

        ModeloAeronave::create($data);

        return redirect()
            ->route('admin.modelos-aeronave.crear')
            ->with('status', 'Modelo de aeronave creado correctamente.');
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

    public function createEquipaje()
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        return view('internal.equipaje-crear', [
            'pasajeros' => Pasajero::query()
                ->with('usuario:id_user,nombre')
                ->orderBy('id_pasajero')
                ->get(),
            'boletos' => Boleto::query()
                ->with([
                    'reserva:id_reserva,id_pasajero,id_vuelo',
                    'reserva.pasajero:id_pasajero,id_user',
                    'reserva.pasajero.usuario:id_user,nombre',
                    'reserva.vuelo:id_vuelo,id_ruta,salida_planificada,llegada_planificada',
                    'reserva.vuelo.ruta:id_ruta,id_aeropuerto_origen,id_aeropuerto_destino',
                    'reserva.vuelo.ruta.aeropuertoOrigen:id_aeropuerto,codigo_iata,ciudad',
                    'reserva.vuelo.ruta.aeropuertoDestino:id_aeropuerto,codigo_iata,ciudad',
                ])
                ->orderByDesc('id_boleto')
                ->limit(50)
                ->get(),
        ]);
    }

    public function storeEquipaje(Request $request)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $data = $request->validate([
            'id_pasajero' => ['required', 'integer', 'exists:pasajero,id_pasajero'],
            'id_boleto' => ['nullable', 'integer', 'exists:boleto,id_boleto'],
            'tipo_equipaje' => ['required', 'in:mano,bodega'],
            'peso_kg' => ['required', 'numeric', 'min:0.01'],
            'estado' => ['required', 'in:registrado,en_cinta,cargado,en_transito,entregado,perdido'],
        ]);


        $data['tipo_equipaje'] = (string) $data['tipo_equipaje'];
        $data['estado'] = (string) $data['estado'];
        $data['etiqueta'] = 'TEMP-' . Str::upper(Str::random(8));

        if (!empty($data['id_boleto'])) {
            $boleto = Boleto::query()->with('reserva')->find($data['id_boleto']);

            if (!$boleto || (int) ($boleto->reserva?->id_pasajero ?? 0) !== (int) $data['id_pasajero']) {
                return back()
                    ->withInput()
                    ->withErrors(['id_boleto' => 'El boleto seleccionado no pertenece al pasajero elegido.']);
            }
        }

        try {
            $equipaje = null;

            DB::transaction(function () use ($data, &$equipaje) {
                $equipaje = Equipaje::create($data);
            });

            if ($equipaje) {
                // Actualizar etiqueta con el ID real
                $equipaje->etiqueta = 'EQ-' . str_pad((string) $equipaje->id_equipaje, 6, '0', STR_PAD_LEFT);
                $equipaje->save();
            } else {
                throw new \Exception('No se pudo crear el registro de equipaje.');
            }
        } catch (\Throwable $exception) {
            return back()->withInput()->withErrors([
                'general' => 'Error de base de datos: ' . $exception->getMessage(),
            ]);
        }

        return redirect()
            ->route('admin.equipaje.crear')
            ->with('status', 'Equipaje registrado correctamente.');
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

        $aeropuertosBusqueda = Aeropuerto::query()
            ->select(['id_aeropuerto', 'codigo_iata', 'nombre', 'ciudad'])
            ->orderBy('codigo_iata')
            ->get()
            ->map(function ($aeropuerto) {
                return [
                    'id' => $aeropuerto->id_aeropuerto,
                    'codigo_iata' => $aeropuerto->codigo_iata,
                    'nombre' => $aeropuerto->nombre,
                    'ciudad' => $aeropuerto->ciudad,
                    'texto' => trim($aeropuerto->codigo_iata . ' ' . $aeropuerto->nombre . ' ' . $aeropuerto->ciudad),
                ];
            })
            ->values();

        $paginator->setCollection($paginator->getCollection()->map(function ($vuelo) {
            $salida = Carbon::parse($vuelo->salida_planificada);
            $llegada = Carbon::parse($vuelo->llegada_planificada);

            $origen = $vuelo->ruta?->aeropuertoOrigen;
            $destino = $vuelo->ruta?->aeropuertoDestino;
            $distanciaRuta = $vuelo->ruta?->distancia_km;
            $aeronave = $vuelo->aeronave;
            $modelo = $aeronave?->modelo;

            $vuelo->ruta_txt = ($origen?->codigo_iata ?? 'N/D') . ' → ' . ($destino?->codigo_iata ?? 'N/D');
            $vuelo->distancia_km = $distanciaRuta ?? 'N/D';
            $vuelo->origen = $origen?->codigo_iata ?? 'N/D';
            $vuelo->origen_ciudad = $origen?->ciudad ?? 'Sin dato';
            $vuelo->destino = $destino?->codigo_iata ?? 'N/D';
            $vuelo->destino_ciudad = $destino?->ciudad ?? 'Sin dato';
            $vuelo->salida = $salida->format('H:i');
            $vuelo->llegada = $llegada->format('H:i');
            $vuelo->fecha = $salida->translatedFormat('d M Y');
            $vuelo->aeronave_matricula = $aeronave?->matricula ?? 'N/D';
            $vuelo->aeronave_modelo = $modelo?->nombre_comercial ?? 'Sin modelo';
            $vuelo->aeronave_fabricante = $modelo?->fabricante ?? 'Sin fabricante';
            $vuelo->aeronave_capacidad = $aeronave?->capacidad_max ?? 'N/D';
            $vuelo->aeronave_autonomia = $modelo?->autonomia_km ?? 'N/D';
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
            'aeropuertosBusqueda' => $aeropuertosBusqueda,
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
            'llegada_planificada' => ['required', 'date', 'after:salida_planificada'],
            'estado' => ['required', 'in:programado,en_vuelo,aterrizado'],
        ]);

        try {
            $vuelo->update($data);
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors([
                'llegada_planificada' => 'La fecha de llegada debe ser posterior a la fecha de salida (restricción de la base de datos).',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors(['general' => 'Error al actualizar el vuelo. Intenta nuevamente.']);
        }

        return redirect()->route('admin.vuelos')->with('status', 'Vuelo actualizado correctamente.');
    }

    public function storeVuelo(Request $request)
    {
        abort_unless(Auth::user() && Auth::user()->rol === 'admin', 403);

        $data = $request->validate([
            'id_ruta' => ['required', 'integer', 'exists:ruta,id_ruta'],
            'id_aeronave' => ['required', 'integer', 'exists:aeronave,id_aeronave'],
            'salida_planificada' => ['required', 'date'],
            'llegada_planificada' => ['required', 'date', 'after:salida_planificada'],
            'estado' => ['required', 'in:programado,en_vuelo,aterrizado'],
        ]);

        try {
            $vuelo = Vuelo::create($data);
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors([
                'llegada_planificada' => 'La fecha de llegada debe ser posterior a la fecha de salida (restricción de la base de datos).',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors(['general' => 'Error al crear el vuelo. Intenta nuevamente.']);
        }

        return redirect()->route('admin.vuelos')->with('status', 'Vuelo creado correctamente.');
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
