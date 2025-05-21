<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\WallpaperController;
use App\Http\Controllers\EstadisticasController;

Route::prefix('v1')->group(function () {

    Route::post('/ingreso', [AuthController::class, 'login']);
    Route::post('/salir', [AuthController::class, 'logout'])->middleware(['admin']);

    Route::middleware('auth:api')->get('/validar', function (Request $request) {
        return response()->json($request->user());
    });

    Route::middleware(['auth:api', 'admin'])->group(function () {

        Route::put('/cuenta/actualizar', [AuthController::class, 'updateProfile']);

        Route::get('/estaciones', [EstacionController::class, 'index']);
        Route::get('/estaciones/{id}', [EstacionController::class, 'show']);

        Route::get('/horarios', [HorarioController::class, 'index']);
        Route::get('/horarios/{id}', [HorarioController::class, 'show']);

        Route::get('/wallpapers', [WallpaperController::class, 'index']);
        Route::get('/wallpapers/buscar', [WallpaperController::class, 'search']);
        Route::get('/wallpapers/{id}', [WallpaperController::class, 'show']);
        Route::post('/wallpapers', [WallpaperController::class, 'store']);
        Route::put('/wallpapers/{id}', [WallpaperController::class, 'update']);
        Route::delete('/wallpapers/{id}', [WallpaperController::class, 'destroy']);

        Route::get('/estadisticas', [EstadisticasController::class, 'obtenerEstadisticas']);

    });

});