<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pasajero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            'Database\\Seeders\\RolTripulacionSeeder',
        ]);

        // Usuario administrador inicial para poder acceder al panel y dar de alta más cuentas.
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nombre' => 'Admin Principal',
                'telefono' => '600000001',
                'contrasenna' => Hash::make('123456'),
                'rol' => 'admin',
            ]
        );

        // Crear o actualizar usuario de prueba
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'nombre' => 'Test User',
                'telefono' => '600000000',
                'contrasenna' => Hash::make('123456'),
                'rol' => 'pasajero',
            ]
        );

        // Crear pasajero vinculado al usuario (la columna id_user en pasajero puede ser NULL,
        // pero aquí enlazamos el pasajero de prueba al user creado). Usamos pasaporte fijo
        // para asegurar unicidad en el seeder.
        $pasajero = Pasajero::updateOrCreate(
            ['pasaporte' => 'TEST-PAS-0001'],
            [
                'id_user' => $user->id_user,            
                'nacionalidad' => 'Desconocida',
            ]
        );

        // Seeder para vuelos y datos relacionados
        $this->call(\Database\Seeders\VuelosSeeder::class);
    }
}
