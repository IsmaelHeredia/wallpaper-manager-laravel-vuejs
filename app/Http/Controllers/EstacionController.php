<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use App\Interfaces\EstacionRepositoryInterface;

/**
 * @OA\Tag(name="Estaciones", description="API para la gestión de estaciones")
 */
class EstacionController extends Controller
{
    protected $estacionRepository;

    public function __construct(EstacionRepositoryInterface $estacionRepository)
    {
        $this->estacionRepository = $estacionRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/estaciones",
     *     summary="Obtener todas las estaciones",
     *     tags={"Estaciones"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=200, description="Listado de estaciones"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function index(): JsonResponse
    {
        $estaciones = $this->estacionRepository->getAll();
        return response()->json($estaciones, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/estaciones/{id}",
     *     summary="Obtener una estación por ID",
     *     tags={"Estaciones"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Detalles de la estación"),
     *     @OA\Response(response=401, description="No autenticado"),
     *     @OA\Response(response=404, description="Estación no encontrada")
     * )
     */
    public function show($id): JsonResponse
    {
        $estacion = $this->estacionRepository->getById($id);

        if (!$estacion) {
            return response()->json(['error' => 'Estación no encontrada'], 404);
        }

        return response()->json($estacion);
    }
}
