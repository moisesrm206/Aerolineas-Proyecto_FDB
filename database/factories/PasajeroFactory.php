<?php

namespace Database\Factories;

use App\Models\Pasajero;
use Illuminate\Database\Eloquent\Factories\Factory;

class PasajeroFactory extends Factory
{
    protected $model = Pasajero::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->optional()->phoneNumber(),
            'pasaporte' => strtoupper($this->faker->bothify('??######')),
            'nacionalidad' => $this->faker->country(),
            'contrasenna' => $this->faker->password(6, 12),
        ];
    }
}
