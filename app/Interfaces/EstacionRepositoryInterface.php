<?php

namespace App\Interfaces;

interface EstacionRepositoryInterface
{
    public function getAll();
    public function getById($id);
}
