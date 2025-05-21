<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WallpaperPaginateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['nullable', 'string'],
            'estaciones_ids' => ['nullable', 'array'],
            'estaciones_ids.*' => ['integer'],
            'horarios_ids' => ['nullable', 'array'],
            'horarios_ids.*' => ['integer'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
