<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WallpaperUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'calificacion' => 'sometimes|required|integer',
            'es_favorita' => 'sometimes|required|boolean',
            'estaciones_ids' => 'nullable|array',
            'estaciones_ids.*' => 'integer|exists:estaciones,id',
            'horarios_ids' => 'nullable|array',
            'horarios_ids.*' => 'integer|exists:horarios,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nombre' => $this->input('nombre'),
            'calificacion' => $this->input('calificacion'),
            'es_favorita' => filter_var($this->input('es_favorita'), FILTER_VALIDATE_BOOLEAN),
            'estaciones_ids' => $this->input('estaciones_ids'),
            'horarios_ids' => $this->input('horarios_ids'),
        ]);
    }
}
