<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $this->user()->id,
            'password' => 'sometimes|string|min:6',
            'photo' => 'sometimes|image|mimes:jpg,png,jpeg|max:2048',
            'currentPassword' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, $this->user()->password)) {
                    $fail('La contraseña actual es incorrecta.');
                }
            }],
        ];
    }
}