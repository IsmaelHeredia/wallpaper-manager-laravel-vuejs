<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface WallpaperRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(Request $request);
    public function update($id, Request $request);
    public function delete($id);
}
