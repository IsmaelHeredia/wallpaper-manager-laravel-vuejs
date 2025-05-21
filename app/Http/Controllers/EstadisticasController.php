<?php

namespace App\Http\Controllers;

use App\Interfaces\EstadisticasRepositoryInterface;
use Illuminate\Http\JsonResponse;

class EstadisticasController extends Controller
{
    protected $estadisticasRepository;

    public function __construct(EstadisticasRepositoryInterface $estadisticasRepository)
    {
        $this->estadisticasRepository = $estadisticasRepository;
    }

    public function obtenerEstadisticas(): JsonResponse
    {
        return response()->json([
            'por_estacion' => $this->estadisticasRepository->wallpapersPorEstacion(),
            'por_horario' => $this->estadisticasRepository->wallpapersPorHorario(),
        ]);
    }
}
