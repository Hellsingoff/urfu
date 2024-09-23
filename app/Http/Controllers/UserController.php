<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\UserVacancyCollectionRequest;
use App\Http\Requests\User\UserVacancyResponseCollectionRequest;
use App\Http\Resources\Resume\ResumeCollectionResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\Vacancy\PaginatedVacancyCollectionResource;
use App\Http\Resources\VacancyResponse\PaginatedVacancyResponseCollectionResource;
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

    public function responses(UserVacancyResponseCollectionRequest $request): PaginatedVacancyResponseCollectionResource
    {
        $data = $request->validated();
        /** @var User $user */
        $user = Auth::user();
        $page = (int) ($data['page'] ?? 1);

        return new PaginatedVacancyResponseCollectionResource(
            $user->responses()->paginate(24, '*', 'page', $page)
        );
    }

    public function vacancies(UserVacancyCollectionRequest $request): PaginatedVacancyCollectionResource
    {
        $data = $request->validated();
        /** @var User $user */
        $user = Auth::user();
        $page = (int) ($data['page'] ?? 1);

        return new PaginatedVacancyCollectionResource(
            $user->vacancies()->paginate(24, '*', 'page', $page)
        );
    }
}
