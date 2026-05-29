<?php

namespace Database\Factories;

use App\Models\Aeropuerto;
use Illuminate\Database\Eloquent\Factories\Factory;

class AeropuertoFactory extends Factory
{
    protected $model = Aeropuerto::class;

    public function definition()
    {
        return [
            'codigo_iata' => strtoupper($this->faker->unique()->lexify('???')),
            'nombre' => $this->faker->city . ' International Airport',
            'ciudad' => $this->faker->city,
            'pais' => $this->faker->country,
        ];
    }
}
