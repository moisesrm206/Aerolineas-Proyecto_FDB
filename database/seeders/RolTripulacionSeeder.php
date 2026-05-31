<?php

namespace Database\Seeders;

use App\Models\RolTripulacion;
use Illuminate\Database\Seeder;

class RolTripulacionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'id_rol' => 1,
                'nombre_rol' => 'piloto',
                'descripcion' => 'Responsable principal de la aeronave y la operación del vuelo.',
            ],
            [
                'id_rol' => 2,
                'nombre_rol' => 'copiloto',
                'descripcion' => 'Asiste al piloto y comparte la operación de cabina.',
            ],
            [
                'id_rol' => 3,
                'nombre_rol' => 'sobrecargo',
                'descripcion' => 'Coordina al personal de cabina y la atención a pasajeros.',
            ],
            [
                'id_rol' => 4,
                'nombre_rol' => 'auxiliar de vuelo',
                'descripcion' => 'Apoya el servicio a bordo y las tareas de seguridad.',
            ],
            [
                'id_rol' => 5,
                'nombre_rol' => 'ingeniero de mantenimiento',
                'descripcion' => 'Supervisa y valida el estado técnico de la aeronave.',
            ],
            [
                'id_rol' => 6,
                'nombre_rol' => 'despachador de vuelo',
                'descripcion' => 'Coordina planes de vuelo, combustible y liberación operativa.',
            ],
        ];

        foreach ($roles as $role) {
            RolTripulacion::updateOrCreate(
                ['id_rol' => $role['id_rol']],
                [
                    'nombre_rol' => $role['nombre_rol'],
                    'descripcion' => $role['descripcion'],
                ]
            );
        }
    }
}