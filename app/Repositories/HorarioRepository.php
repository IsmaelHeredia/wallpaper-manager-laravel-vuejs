<?php

namespace App\Repositories;

use App\Models\Horario;
use App\Interfaces\HorarioRepositoryInterface;

class HorarioRepository implements HorarioRepositoryInterface
{
    public function getAll()
    {
        return Horario::all();
    }

    public function getById($id)
    {
        return Horario::findOrFail($id);
    }
}
