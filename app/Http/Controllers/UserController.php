<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Resume\ResumeCollectionResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

readonly class UserController
{
    public function profile(): UserResource
    {
        /** @var User $user */
        $user = Auth::user();

        return new UserResource($user);
    }

    public function resume(): ResumeCollectionResource
    {
        /** @var User $user */
        $user = Auth::user();

        return new ResumeCollectionResource($user->resume);
    }
}
