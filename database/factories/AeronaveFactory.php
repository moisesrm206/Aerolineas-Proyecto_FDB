<?php

namespace Database\Factories;

use App\Models\Aeronave;
use App\Models\ModeloAeronave;
use Illuminate\Database\Eloquent\Factories\Factory;

class AeronaveFactory extends Factory
{
    protected $model = Aeronave::class;

    public function definition()
    {
        $modelo = ModeloAeronave::inRandomOrder(10)->first();
        if (!$modelo) {
            $modelo = ModeloAeronave::create([
                'fabricante' => $this->faker->company,
                'nombre_comercial' => $this->faker->word . ' ' . $this->faker->numerify('#'),
                'autonomia_km' => $this->faker->numberBetween(4000, 15000),
            ]);
        }

        return [
            'id_modelo' => $modelo->id_modelo,
            'matricula' => strtoupper($this->faker->bothify('EC-???')),
            'capacidad_max' => $this->faker->numberBetween(100, 350),
        ];
    }
}
