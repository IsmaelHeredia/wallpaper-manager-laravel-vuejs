<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Estacion;
use App\Models\Horario;
use App\Models\Wallpaper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@localhost.com',
            'password' => Hash::make('admin'),
            'photo' => 'avatar.png',
            'role' => 'admin',
        ]);

        $estaciones = [
            ['nombre' => 'Verano', 'fecha_inicio' => '2024-12-21', 'fecha_fin' => '2024-03-20'],
            ['nombre' => 'Otoño', 'fecha_inicio' => '2024-03-21', 'fecha_fin' => '2024-06-20'],
            ['nombre' => 'Invierno', 'fecha_inicio' => '2024-06-21', 'fecha_fin' => '2024-09-20'],
            ['nombre' => 'Primavera', 'fecha_inicio' => '2024-09-21', 'fecha_fin' => '2024-12-20'],
        ];

        foreach ($estaciones as $estacion) {
            Estacion::updateOrCreate(['nombre' => $estacion['nombre']], $estacion);
        }

        $horarios = [
            ['nombre' => 'Amanecer', 'hora_inicio' => '05:00:00', 'hora_fin' => '07:59:59'],
            ['nombre' => 'Mañana', 'hora_inicio' => '08:00:00', 'hora_fin' => '11:59:59'],
            ['nombre' => 'Tarde', 'hora_inicio' => '12:00:00', 'hora_fin' => '17:59:59'],
            ['nombre' => 'Noche', 'hora_inicio' => '18:00:00', 'hora_fin' => '04:59:59']
        ];

        Horario::insert($horarios);

        $estacionesIds = Estacion::pluck('id')->toArray();
        $horariosIds = Horario::pluck('id')->toArray();

        for ($i = 1; $i <= 30; $i++) {
            $wallpaper = Wallpaper::create([
                'nombre' => "Wallpaper $i",
                'imagen' => 'fondo.jpg',
                'calificacion' => rand(1, 5),
                'es_favorita' => (bool) rand(0, 1),
            ]);

            $wallpaper->estaciones()->sync(
                collect($estacionesIds)->random(rand(1, 2))->toArray()
            );

            $wallpaper->horarios()->sync(
                collect($horariosIds)->random(rand(1, 2))->toArray()
            );
        }
    }
}
