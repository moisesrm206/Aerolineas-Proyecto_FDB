<?php

namespace Database\Factories;

use App\Models\ModeloAeronave;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModeloAeronaveFactory extends Factory
{
    protected $model = ModeloAeronave::class;

    public function definition()
    {
        return [
            'fabricante' => $this->faker->company,
            'nombre_comercial' => $this->faker->word . ' ' . $this->faker->numerify('#'),
            'autonomia_km' => $this->faker->numberBetween(4000, 15000),
        ];
    }
}
