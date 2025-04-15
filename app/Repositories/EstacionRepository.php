<?php

namespace App\Repositories;

use App\Models\Estacion;
use App\Interfaces\EstacionRepositoryInterface;

class EstacionRepository implements EstacionRepositoryInterface
{
    public function getAll()
    {
        return Estacion::all();
    }

    public function getById($id)
    {
        return Estacion::findOrFail($id);
    }
}
