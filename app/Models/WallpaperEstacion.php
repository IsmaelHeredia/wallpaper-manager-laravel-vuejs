<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class WallpaperEstacion extends Pivot
{
    use HasFactory;

    protected $table = 'wallpaper_estacion';
}