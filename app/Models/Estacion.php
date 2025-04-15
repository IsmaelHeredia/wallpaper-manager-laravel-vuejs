<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estacion extends Model
{
    use HasFactory;
    protected $table = 'estaciones';

    protected $fillable = ['nombre', 'fecha_inicio', 'fecha_fin'];

    public function wallpapers()
    {
        return $this->belongsToMany(Wallpaper::class, 'wallpaper_estacion');
    }
}
