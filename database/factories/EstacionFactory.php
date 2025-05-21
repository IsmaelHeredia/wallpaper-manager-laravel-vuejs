<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EstacionFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $endDate = $this->faker->dateTimeBetween($startDate, '+6 months');
    
        return [
            'nombre' => $this->faker->unique()->word(),
            'fecha_inicio' => $startDate,
            'fecha_fin' => $endDate,
        ];
    }
}
