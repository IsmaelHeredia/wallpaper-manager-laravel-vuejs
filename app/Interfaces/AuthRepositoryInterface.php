<?php

namespace App\Interfaces;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;

interface AuthRepositoryInterface
{
    public function login(LoginRequest $request, ServerRequestInterface $serverRequest);
    public function logout(Request $request);
    public function updateProfile(UpdateProfileRequest $request);
}
