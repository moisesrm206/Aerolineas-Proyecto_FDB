<?php

namespace Database\Factories;

use App\Models\Ruta;
use App\Models\Aeropuerto;
use Illuminate\Database\Eloquent\Factories\Factory;

class RutaFactory extends Factory
{
    protected $model = Ruta::class;

    public function definition()
    {
        $origen = Aeropuerto::inRandomOrder(1)->first();
        if (!$origen) {
            $origen = Aeropuerto::create([
                'codigo_iata' => strtoupper($this->faker->unique()->lexify('???')),
                'nombre' => $this->faker->city . ' International Airport',
                'ciudad' => $this->faker->city,
                'pais' => $this->faker->country,
            ]);
        }

        $destino = Aeropuerto::where('id_aeropuerto', '!=', $origen->id_aeropuerto,TRUE)->inRandomOrder(1)->first();
        if (!$destino) {
            $destino = Aeropuerto::create([
                'codigo_iata' => strtoupper($this->faker->unique()->lexify('???')),
                'nombre' => $this->faker->city . ' International Airport',
                'ciudad' => $this->faker->city,
                'pais' => $this->faker->country,
            ]);
        }

        return [
            'id_aeropuerto_origen' => $origen->id_aeropuerto,
            'id_aeropuerto_destino' => $destino->id_aeropuerto,
            'distancia_km' => $this->faker->numberBetween(200, 9000),
        ];
    }
}
