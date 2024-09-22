<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Register\RegisterRequest;
use App\Http\Resources\TokenResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController
{
    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request): TokenResource
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make((string) $request->string('password')),
        ]);

        Auth::login($user);
        $token = $user->createToken('api', ['user'])->plainTextToken;

        return new TokenResource($token);
    }
}
