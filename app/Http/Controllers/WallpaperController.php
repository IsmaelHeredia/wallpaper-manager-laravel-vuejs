<?php

namespace App\Http\Controllers;

use App\Http\Requests\WallpaperCreateRequest;
use App\Http\Requests\WallpaperUpdateRequest;
use App\Interfaces\WallpaperRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Wallpapers", description="API para la gestión de wallpapers")
 */
class WallpaperController extends Controller
{
    protected $wallpaperRepository;

    public function __construct(WallpaperRepositoryInterface $wallpaperRepository)
    {
        $this->wallpaperRepository = $wallpaperRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/wallpapers",
     *     summary="Obtener todos los wallpapers",
     *     tags={"Wallpapers"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=200, description="Listado de wallpapers"),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function index(): JsonResponse
    {
        $wallpapers = $this->wallpaperRepository->getAll();
        return response()->json($wallpapers, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/wallpapers",
     *     summary="Crear un nuevo wallpaper",
     *     tags={"Wallpapers"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(property="ruta", type="string", format="binary"),
     *                 @OA\Property(property="es_favorita", type="integer"),
     *                 @OA\Property(property="estaciones_ids", type="array", @OA\Items(type="integer")),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Wallpaper creado exitosamente"),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(response=400, description="Error en los datos de entrada")
     * )
     */
    public function store(WallpaperCreateRequest $request): JsonResponse
    {
        $wallpaper = $this->wallpaperRepository->create($request);
        return response()->json($wallpaper, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/wallpapers/{id}",
     *     summary="Obtener un wallpaper por ID",
     *     tags={"Wallpapers"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Wallpaper encontrado"),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(response=404, description="Wallpaper no encontrado")
     * )
     */
    public function show($id): JsonResponse
    {
        $wallpaper = $this->wallpaperRepository->getById($id);
        if (!$wallpaper) {
            return response()->json(['message' => 'Wallpaper no encontrado'], 404);
        }
        return response()->json($wallpaper, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/wallpapers/{id}",
     *     summary="Actualizar un wallpaper existente",
     *     tags={"Wallpapers"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(property="ruta", type="string", format="binary"),
     *                 @OA\Property(property="es_favorita", type="integer"),
     *                 @OA\Property(property="estaciones_ids", type="array", @OA\Items(type="integer")),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Wallpaper actualizado exitosamente"),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(response=404, description="Wallpaper no encontrado")
     * )
     */
    public function update($id, WallpaperUpdateRequest $request): JsonResponse
    {
        $wallpaper = $this->wallpaperRepository->update($id, $request);
        return response()->json($wallpaper, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/wallpapers/{id}",
     *     summary="Eliminar un wallpaper",
     *     tags={"Wallpapers"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Wallpaper eliminado exitosamente"),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(response=404, description="Wallpaper no encontrado")
     * )
     */
    public function destroy($id): JsonResponse
    {
        $this->wallpaperRepository->delete($id);
        return response()->json(['message' => 'Wallpaper eliminado exitosamente'], 200);
    }
}
