<?php

namespace App\Http\Controllers;

use App\Http\Requests\WallpaperCreateRequest;
use App\Http\Requests\WallpaperUpdateRequest;
use App\Interfaces\WallpaperRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Illuminate\Http\Response;

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
     * @OA\Get(
     *     path="/api/wallpapers/buscar",
     *     summary="Listar wallpapers paginados con filtros opcionales",
     *     tags={"Wallpapers"},
     *     security={{"passport": {}}},
     *     @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         description="Buscar por nombre",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="estaciones_ids[]",
     *         in="query",
     *         description="Filtrar por IDs de estaciones",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer"))
     *     ),
     *     @OA\Parameter(
     *         name="horarios_ids[]",
     *         in="query",
     *         description="Filtrar por IDs de horarios",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer"))
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Cantidad de resultados por página",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado paginado de wallpapers",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(property="imagen", type="string"),
     *                 @OA\Property(property="calificacion", type="integer"),
     *                 @OA\Property(property="es_favorita", type="boolean"),
     *                 @OA\Property(property="estaciones", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="nombre", type="string")
     *                 )),
     *                 @OA\Property(property="horarios", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="rango", type="string")
     *                 )),
     *             )),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado"
     *     )
     * )
     */
    public function search(Request $request)
    {
        return response()->json($this->wallpaperRepository->paginate($request));
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
    public function destroy($id): Response
    {
        $this->wallpaperRepository->delete($id);
        return response()->noContent();
    }
}
