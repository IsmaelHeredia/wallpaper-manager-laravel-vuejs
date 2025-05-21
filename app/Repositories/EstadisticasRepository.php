<?php

namespace App\Repositories;

use App\Models\Estacion;
use App\Models\Horario;
use App\Interfaces\EstadisticasRepositoryInterface;

class EstadisticasRepository implements EstadisticasRepositoryInterface
{
    public function wallpapersPorEstacion()
    {
        return Estacion::withCount('wallpapers')
            ->get()
            ->map(function ($estacion) {
                return [
                    'nombre' => $estacion->nombre,
                    'cantidad' => $estacion->wallpapers_count,
                ];
            });
    }

    public function wallpapersPorHorario()
    {
        return Horario::withCount('wallpapers')
            ->get()
            ->map(function ($horario) {
                return [
                    'nombre' => $horario->nombre,
                    'cantidad' => $horario->wallpapers_count,
                ];
            });
    }
}
