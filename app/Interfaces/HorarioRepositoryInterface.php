<?php

namespace App\Interfaces;

interface HorarioRepositoryInterface
{
    public function getAll();
    public function getById($id);
}
