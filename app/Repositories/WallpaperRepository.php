<?php

namespace App\Repositories;

use App\Models\Wallpaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\WallpaperRepositoryInterface;

class WallpaperRepository implements WallpaperRepositoryInterface
{
    private const UPLOAD_PATH = 'wallpapers';

    public function getAll()
    {
        return Wallpaper::all();
    }

    public function getById($id)
    {
        return Wallpaper::findOrFail($id);
    }

    public function create(Request $request)
    {
        $imageName = null;

        if ($request->hasFile('imagen')) {
            $imageName = $request->file('imagen')->hashName();
            $request->file('imagen')->storeAs(self::UPLOAD_PATH, $imageName, 'public');
        }

        $wallpaper = Wallpaper::create([
            'nombre' => $request->nombre,
            'imagen' => $imageName,
            'calificacion' => $request->calificacion,
            'es_favorita' => $request->es_favorita,
        ]);

        if ($request->has('estaciones_ids')) {
            $wallpaper->estaciones()->sync($request->estaciones_ids);
        }

        if ($request->has('horarios_ids')) {
            $wallpaper->horarios()->sync($request->horarios_ids);
        }

        return $wallpaper;
    }

    public function update($id, Request $request)
    {
        $wallpaper = Wallpaper::findOrFail($id);

        $data = [
            'nombre' => $request->nombre,
            'calificacion' => $request->calificacion,
            'es_favorita' => $request->es_favorita,
        ];

        if ($request->hasFile('imagen')) {
            if ($wallpaper->imagen && Storage::disk('public')->exists(self::UPLOAD_PATH . '/' . $wallpaper->imagen)) {
                Storage::disk('public')->delete(self::UPLOAD_PATH . '/' . $wallpaper->imagen);
            }

            $imageName = $request->file('imagen')->hashName();
            $request->file('imagen')->storeAs(self::UPLOAD_PATH, $imageName, 'public');
            $data['imagen'] = $imageName;
        }

        $wallpaper->update($data);

        if ($request->has('estaciones_ids')) {
            $wallpaper->estaciones()->sync($request->estaciones_ids);
        }

        if ($request->has('horarios_ids')) {
            $wallpaper->horarios()->sync($request->horarios_ids);
        }

        return $wallpaper;
    }

    public function delete($id)
    {
        $wallpaper = Wallpaper::findOrFail($id);

        if ($wallpaper->imagen && Storage::disk('public')->exists(self::UPLOAD_PATH . '/' . $wallpaper->imagen)) {
            Storage::disk('public')->delete(self::UPLOAD_PATH . '/' . $wallpaper->imagen);
        }

        $wallpaper->estaciones()->detach();
        $wallpaper->horarios()->detach();

        $wallpaper->delete();
    }
}
