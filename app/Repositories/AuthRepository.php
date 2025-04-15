<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthRepository implements AuthRepositoryInterface
{
    private const UPLOAD_PATH = 'photos';

    public function login(LoginRequest $request, ServerRequestInterface $serverRequest)
    {
        $data = [
            'grant_type' => 'password',
            'client_id' => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username' => $request->input('email'),
            'password' => $request->input('password'),
            'scope' => '*',
        ];

        $serverRequest = $serverRequest->withParsedBody($data);

        return app()->make(AccessTokenController::class)->issueToken($serverRequest);
    }

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $request->user()->token()->revoke();

        return response()->json(['message' => 'La sesión fue cerrada correctamente'], 200);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $validatedData = $request->validated();

        if ($request->has('name')) {
            $user->name = $validatedData['name'];
        }

        if ($request->has('email')) {
            $user->email = $validatedData['email'];
        }

        if ($request->has('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        if ($request->hasFile('photo')) {

            if (
                $user->photo &&
                Storage::disk('public')->exists(self::UPLOAD_PATH . '/' . $user->photo)
            ) {
                Storage::disk('public')->delete(self::UPLOAD_PATH . '/' . $user->photo);
            }

            $photoPath = $request->file('photo')->store(self::UPLOAD_PATH, 'public');

            $user->photo = basename($photoPath);
        }

        $user->save();

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'user' => [
                'id' => $user->id,
                'nombre' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo ? asset('storage/' . self::UPLOAD_PATH . '/' . $user->photo) : null,
            ]
        ], 200);
    }
}