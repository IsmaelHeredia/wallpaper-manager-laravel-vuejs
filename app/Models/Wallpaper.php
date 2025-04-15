<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallpaper extends Model
{
    use HasFactory;
    protected $table = 'wallpapers';

    protected $fillable = ['nombre', 'imagen', 'calificacion', 'es_favorita'];

    public function estaciones()
    {
        return $this->belongsToMany(Estacion::class, 'wallpaper_estacion');
    }

    public function horarios() {
        return $this->belongsToMany(Horario::class);
    }

}