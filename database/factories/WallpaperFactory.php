<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WallpaperFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->words(3, true),
            'imagen' => 'wallpapers/fake.jpg',
            'calificacion' => $this->faker->numberBetween(1, 5),
            'es_favorita' => $this->faker->numberBetween(0, 1)
        ];
    }
}
