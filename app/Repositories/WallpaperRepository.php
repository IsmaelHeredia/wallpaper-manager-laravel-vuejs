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
        $query = Wallpaper::query();

        $query->select(['id', 'nombre', 'imagen', 'calificacion', 'es_favorita'])
            ->with([
                'estaciones:id,nombre',
                'horarios:id,nombre'
            ])
            ->latest('updated_at');

        $wallpapers = $query->get();

        $wallpapers->each(function ($wallpaper) {
            $wallpaper->estaciones->each->makeHidden('pivot');
            $wallpaper->horarios->each->makeHidden('pivot');
        });

        return $wallpapers;
    }

    public function paginate(Request $request)
    {
        $query = Wallpaper::query();

        if (!empty($request->nombre)) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if (!empty($request->estaciones_ids)) {
            $query->whereHas('estaciones', function ($q) use ($request) {
                $q->whereIn('estaciones.id', $request->estaciones_ids);
            });
        }

        if (!empty($request->horarios_ids)) {
            $query->whereHas('horarios', function ($q) use ($request) {
                $q->whereIn('horarios.id', $request->horarios_ids);
            });
        }

        $query->select(['id', 'nombre', 'imagen', 'calificacion', 'es_favorita'])
            ->with([
                'estaciones:id,nombre',
                'horarios:id,nombre'
            ])
            ->latest('updated_at');

        if ($request->input('per_page') === 'all') {

            $wallpapers = $query->get();

            $wallpapers->each(function ($wallpaper) {
                $wallpaper->estaciones->each->makeHidden('pivot');
                $wallpaper->horarios->each->makeHidden('pivot');
            });

            return response()->json([
                'data' => $wallpapers,
                'total' => $wallpapers->count(),
            ]);
        }

        $wallpapers = $query->paginate((int) $request->input('per_page', 10));

        $wallpapers->getCollection()->transform(function ($wallpaper) {
            $wallpaper->estaciones->each->makeHidden('pivot');
            $wallpaper->horarios->each->makeHidden('pivot');
            return $wallpaper;
        });

        return $wallpapers;
    }

    public function getById($id)
    {
        $wallpaper = Wallpaper::with([
            'estaciones:id,nombre',
            'horarios:id,nombre'
        ])->findOrFail($id);

        $wallpaper->estaciones->each->makeHidden('pivot');
        $wallpaper->horarios->each->makeHidden('pivot');

        return $wallpaper;
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
