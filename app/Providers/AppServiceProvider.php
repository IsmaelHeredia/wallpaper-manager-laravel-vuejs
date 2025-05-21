<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\WallpaperRepositoryInterface;
use App\Repositories\WallpaperRepository;

use App\Interfaces\EstacionRepositoryInterface;
use App\Repositories\EstacionRepository;

use App\Interfaces\HorarioRepositoryInterface;
use App\Repositories\HorarioRepository;

use App\Interfaces\EstadisticasRepositoryInterface;
use App\Repositories\EstadisticasRepository;

use App\Interfaces\AuthRepositoryInterface;
use App\Repositories\AuthRepository;

use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{    
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(EstacionRepositoryInterface::class, EstacionRepository::class);
        $this->app->bind(WallpaperRepositoryInterface::class, WallpaperRepository::class);
        $this->app->bind(HorarioRepositoryInterface::class, HorarioRepository::class);
        $this->app->bind(EstadisticasRepositoryInterface::class, EstadisticasRepository::class);
    }

    public function boot(): void
    {
        Passport::enablePasswordGrant();
    }
}
