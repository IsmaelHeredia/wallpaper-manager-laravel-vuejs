<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use App\Interfaces\HorarioRepositoryInterface;

/**
 * @OA\Tag(name="Horarios", description="API para la gestión de horarios")
 */
class HorarioController extends Controller
{
    protected $horarioRepository;

    public function __construct(HorarioRepositoryInterface $horarioRepository)
    {
        $this->horarioRepository = $horarioRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/horarios",
     *     summary="Obtener todos los horarios",
     *     tags={"Horarios"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=200, description="Listado de horarios"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function index(): JsonResponse
    {
        $horarios = $this->horarioRepository->getAll();
        return response()->json($horarios, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/horarios/{id}",
     *     summary="Obtener un horario por ID",
     *     tags={"Horarios"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Detalles del horario"),
     *     @OA\Response(response=401, description="No autenticado"),
     *     @OA\Response(response=404, description="Horario no encontrado")
     * )
     */
    public function show($id): JsonResponse
    {
        $horario = $this->horarioRepository->getById($id);

        if (!$horario) {
            return response()->json(['error' => 'Horario no encontrado'], 404);
        }

        return response()->json($horario);
    }
}
