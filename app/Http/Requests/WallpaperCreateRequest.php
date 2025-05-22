<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WallpaperCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'calificacion' => 'required|integer',
            'es_favorita' => 'required|boolean',
            'estaciones_ids' => 'nullable|array',
            'estaciones_ids.*' => 'integer|exists:estaciones,id',
            'horarios_ids' => 'nullable|array',
            'horarios_ids.*' => 'integer|exists:horarios,id',
        ];
    }
}
