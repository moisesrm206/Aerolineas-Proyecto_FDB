@extends('templates.app')

@section('title', 'Gestión de Equipaje | AeroControl')

@section('content')
    <section class="space-y-8">
        <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr] lg:items-end">
            <div>
                <p class="section-label">Operación activa</p>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl">Gestión de Equipaje</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-white/70 sm:text-lg">
                    Esta pantalla está lista para conectarse con la base de datos. Por ahora muestra la estructura visual y un estado vacío, sin inventar registros que todavía no existen.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="glass-panel rounded-[1.5rem] p-5">
                    <p class="text-sm text-white/55">Estado</p>
                    <p class="mt-2 text-3xl font-bold text-white">Pendiente</p>
                    <p class="mt-2 text-sm text-cyan-300">Listo para BD</p>
                </div>
                <div class="glass-panel rounded-[1.5rem] p-5">
                    <p class="text-sm text-white/55">Origen</p>
                    <p class="mt-2 text-3xl font-bold text-white">Manual</p>
                    <p class="mt-2 text-sm text-amber-300">Sin seeders aún</p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-2xl text-blue-600">🧳</div>
                    <div>
                        <h2 class="text-2xl font-semibold text-white">Equipaje de Mano</h2>
                        <p class="mt-1 text-sm text-white/55">Reglas operativas pendientes de parametrizar</p>
                    </div>
                </div>

                <ul class="mt-6 space-y-3 text-sm leading-7 text-white/75">
                    <li>• Dimensiones máximas: por definir en BD</li>
                    <li>• Peso máximo: por definir en BD</li>
                    <li>• Validación: antes de abordar</li>
                    <li>• Disponibilidad: una pieza por pasajero</li>
                </ul>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-2xl text-blue-600">📦</div>
                    <div>
                        <h2 class="text-2xl font-semibold text-white">Equipaje de Bodega</h2>
                        <p class="mt-1 text-sm text-white/55">Reglas operativas pendientes de parametrizar</p>
                    </div>
                </div>

                <ul class="mt-6 space-y-3 text-sm leading-7 text-white/75">
                    <li>• Dimensiones máximas: por definir en BD</li>
                    <li>• Peso máximo: por definir en BD</li>
                    <li>• Costo adicional: por definir en BD</li>
                    <li>• Entrega: mostrador de documentación</li>
                </ul>
            </article>
        </div>

        <section class="glass-panel rounded-[2rem] p-6 sm:p-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="section-label">Mi Equipaje</p>
                    <h2 class="mt-4 text-3xl font-bold text-white">Aún no hay equipaje cargado</h2>
                    <p class="mt-3 max-w-3xl text-white/65">
                        Cuando conectes el controlador y la base de datos, aquí se listarán los equipajes reales asociados a cada pasajero y boleto.
                    </p>
                </div>
            </div>

            <div class="mt-8 rounded-[1.5rem] border border-white/10 bg-white/[0.03] p-6">
                <div class="flex items-center gap-4 text-white/70">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full border border-cyan-300/30 bg-cyan-300/10 text-cyan-300">i</div>
                    <div>
                        <p class="text-lg font-semibold text-white">Sin datos todavía</p>
                        <p class="mt-1 text-sm text-white/60">Este bloque espera resultados de equipaje, boleto y pasajero cuando termines de validar la arquitectura.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-[2rem] border border-amber-300/40 bg-[linear-gradient(180deg,rgba(250,204,21,0.18),rgba(255,255,255,0.02))] p-6 sm:p-8">
            <h3 class="text-2xl font-semibold text-white">Artículos prohibidos</h3>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <p class="text-sm font-semibold text-white/80">Equipaje de mano</p>
                    <ul class="mt-3 space-y-2 text-sm text-white/75">
                        <li>• Líquidos no autorizados</li>
                        <li>• Objetos punzocortantes</li>
                        <li>• Baterías sueltas no permitidas</li>
                    </ul>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white/80">Equipaje de bodega</p>
                    <ul class="mt-3 space-y-2 text-sm text-white/75">
                        <li>• Materiales inflamables</li>
                        <li>• Sustancias tóxicas</li>
                        <li>• Dispositivos explosivos</li>
                    </ul>
                </div>
            </div>
        </section>
    </section>
@endsection