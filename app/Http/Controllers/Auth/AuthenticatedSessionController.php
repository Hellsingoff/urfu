<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     * @throws ValidationException
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();
        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('api', ['user'])->plainTextToken;

        return response()->json(['token' => $token]); // todo meh
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $token = $request->bearerToken();
        preg_match('/^(\d+)\|.+$/', $token, $match);
        $user->tokens()->where('id', $match[1])->delete();

        return response()->json(['success' => true]); // todo meh
    }
}
