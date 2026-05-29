<?php

namespace Database\Factories;

use App\Models\Vuelo;
use App\Models\Ruta;
use App\Models\Aeronave;
use App\Models\Aeropuerto;
use Illuminate\Database\Eloquent\Factories\Factory;

class VueloFactory extends Factory
{
    protected $model = Vuelo::class;

    public function definition()
    {
        $ruta = Ruta::inRandomOrder(10)->first();
        if (!$ruta) {
            // Ensure at least two airports exist
            $a1 = \App\Models\Aeropuerto::inRandomOrder(1)->first();
            if (!$a1) {
                $a1 = \App\Models\Aeropuerto::create([
                    'codigo_iata' => strtoupper($this->faker->unique()->lexify('???')),
                    'nombre' => $this->faker->city . ' International Airport',
                    'ciudad' => $this->faker->city,
                    'pais' => $this->faker->country,
                ]);
            }
            $a2 = \App\Models\Aeropuerto::where('id_aeropuerto', '!=', $a1->id_aeropuerto, TRUE)->inRandomOrder()->first();
            if (!$a2) {
                $a2 = \App\Models\Aeropuerto::create([
                    'codigo_iata' => strtoupper($this->faker->unique()->lexify('???')),
                    'nombre' => $this->faker->city . ' International Airport',
                    'ciudad' => $this->faker->city,
                    'pais' => $this->faker->country,
                ]);
            }
            $ruta = Ruta::create([
                'id_aeropuerto_origen' => $a1->id_aeropuerto,
                'id_aeropuerto_destino' => $a2->id_aeropuerto,
                'distancia_km' => $this->faker->numberBetween(200, 9000),
            ]);
        }

        $aeronave = Aeronave::inRandomOrder(2)->first();
        if (!$aeronave) {
            $aeronave = Aeronave::create([
                'id_modelo' => \App\Models\ModeloAeronave::first(5)?->id_modelo ?? 1,
                'matricula' => strtoupper($this->faker->bothify('EC-???')),
                'capacidad_max' => $this->faker->numberBetween(100, 350),
            ]);
        }
        $salida = $this->faker->dateTimeBetween('now', '+14 days');
        $duracionMin = $this->faker->numberBetween(60, 720); // 1h - 12h
        $llegada = (clone $salida)->modify("+{$duracionMin} minutes");

        $estados = ['programado', 'en_vuelo', 'aterrizado', 'cancelado'];

        return [
            'id_ruta' => $ruta->id_ruta,
            'id_aeronave' => $aeronave->id_aeronave,
            'salida_planificada' => $salida->format('Y-m-d H:i:s'),
            'llegada_planificada' => $llegada->format('Y-m-d H:i:s'),
            'estado' => $this->faker->randomElement($estados),
        ];
    }
}
