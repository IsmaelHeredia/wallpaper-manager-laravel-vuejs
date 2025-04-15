<?php

namespace App\Http\Controllers;

use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateProfileRequest;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @OA\Info(
 *     title="API para Wallpapers",
 *     version="1.0",
 *     description="API que gestiona el sistema de wallpapers",
 *     @OA\Contact(
 *         email="soporte@localhost.com"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\Tag(name="Autenticación", description="Manejo de autenticación de usuarios con OAuth2")
 */
class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/ingreso",
     *     summary="Iniciar sesión con OAuth2 (Password Grant)",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="admin@localhost.com"),
     *             @OA\Property(property="password", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Inicio de sesión exitoso, retorna el token de acceso"),
     *     @OA\Response(response=401, description="Credenciales incorrectas")
     * )
     */
    public function login(LoginRequest $request, ServerRequestInterface $serverRequest)
    {
        return $this->authRepository->login($request, $serverRequest);
    }

    /**
     * @OA\Post(
     *     path="/api/salir",
     *     summary="Cerrar sesión y revocar el token de acceso",
     *     tags={"Autenticación"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=200, description="Sesión cerrada exitosamente"),
     *     @OA\Response(response=401, description="Usuario no autenticado")
     * )
     */
    public function logout(Request $request)
    {
        return $this->authRepository->logout($request);
    }

    /**
     * @OA\Put(
     *     path="/api/cuenta/actualizar",
     *     summary="Actualizar los datos del usuario autenticado",
     *     tags={"Autenticación"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Nuevo Nombre"),
     *                 @OA\Property(property="email", type="string", example="nuevoemail@example.com"),
     *                 @OA\Property(property="password", type="string", example="nuevaclave123"),
     *                 @OA\Property(property="photo", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Usuario actualizado exitosamente"),
     *     @OA\Response(response=401, description="Usuario no autenticado")
     * )
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        return $this->authRepository->updateProfile($request);
    }
}