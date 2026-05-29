<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aeropuerto;
use App\Models\ModeloAeronave;
use App\Models\Aeronave;
use App\Models\Ruta;
use App\Models\Vuelo;

class VuelosSeeder extends Seeder
{
    public function run()
    {
        // Crear algunos aeropuertos con códigos reales para pruebas
        $airports = [
            ['codigo_iata' => 'MAD', 'nombre' => 'Adolfo Suárez Madrid-Barajas', 'ciudad' => 'Madrid', 'pais' => 'España'],
            ['codigo_iata' => 'BCN', 'nombre' => 'Barcelona-El Prat', 'ciudad' => 'Barcelona', 'pais' => 'España'],
            ['codigo_iata' => 'MIA', 'nombre' => 'Miami International', 'ciudad' => 'Miami', 'pais' => 'Estados Unidos'],
            ['codigo_iata' => 'JFK', 'nombre' => 'John F. Kennedy', 'ciudad' => 'New York', 'pais' => 'Estados Unidos'],
            ['codigo_iata' => 'NRT', 'nombre' => 'Narita', 'ciudad' => 'Tokio', 'pais' => 'Japón'],
            ['codigo_iata' => 'LHR', 'nombre' => 'Heathrow', 'ciudad' => 'Londres', 'pais' => 'Reino Unido'],
        ];

        foreach ($airports as $a) {
            Aeropuerto::updateOrCreate(['codigo_iata' => $a['codigo_iata']], $a);
        }

        // Modelos y aeronaves
        ModeloAeronave::factory()->count(3)->create();
        Aeronave::factory()->count(4)->create();

        // Rutas entre aeropuertos
        $iata = Aeropuerto::all()->pluck('id_aeropuerto')->toArray();
        // Crear algunas rutas aleatorias
        for ($i = 0; $i < 8; $i++) {
            $origen = $iata[array_rand($iata)];
            $destino = $origen;
            while ($destino === $origen) {
                $destino = $iata[array_rand($iata)];
            }
            Ruta::create([
                'id_aeropuerto_origen' => $origen,
                'id_aeropuerto_destino' => $destino,
                'distancia_km' => rand(200, 9000),
            ]);
        }

        // Vuelos
        Vuelo::factory()->count(15)->create();
    }
}
