<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class HorarioFactory extends Factory
{
    public function definition(): array
    {
        $horaInicio = $this->faker->time('H:i');
        $horaFin = Carbon::createFromTimeString($horaInicio)->addHours(rand(1, 4))->format('H:i');
    
        return [
            'nombre' => $this->faker->unique()->word(),
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
        ];
    }
}
