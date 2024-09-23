<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\VacancyFiltersDTO;
use App\Enum\VacancyStatus;
use App\Handlers\Vacancy\CreateVacancyCommand;
use App\Handlers\Vacancy\CreateVacancyHandler;
use App\Handlers\Vacancy\GetVacancyCollectionCommand;
use App\Handlers\Vacancy\GetVacancyCollectionHandler;
use App\Handlers\Vacancy\RemoveVacancyCommand;
use App\Handlers\Vacancy\RemoveVacancyHandler;
use App\Handlers\Vacancy\UpdateVacancyCommand;
use App\Handlers\Vacancy\UpdateVacancyHandler;
use App\Http\Requests\Vacancy\VacancyCollectionRequest;
use App\Http\Requests\Vacancy\VacancyShowRequest;
use App\Http\Requests\Vacancy\VacancyStoreRequest;
use App\Http\Requests\Vacancy\VacancyUpdateRequest;
use App\Http\Requests\VacancyResponse\VacancyResponseCollectionRequest;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\Vacancy\PaginatedVacancyCollectionResource;
use App\Http\Resources\Vacancy\VacancyResource;
use App\Http\Resources\Vacancy\VacancyTranslationsResource;
use App\Http\Resources\VacancyResponse\PaginatedVacancyResponseCollectionResource;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

readonly class VacancyController
{
    public function __construct(
        private CreateVacancyHandler $createVacancyHandler,
        private UpdateVacancyHandler $updateVacancyHandler,
        private RemoveVacancyHandler $removeVacancyHandler,
        private GetVacancyCollectionHandler $getVacancyCollectionHandler,
    ){
    }

    public function store(VacancyStoreRequest $request): VacancyTranslationsResource
    {
        $data = $request->validated();
        $languages = $data['languages'];
        $names = [];
        $descriptions = [];
        foreach ($languages as $language) {
            $names[$language] = $data['name'][$language];
            $descriptions[$language] = $data['description'][$language];
        }
        $vacancy = $this->createVacancyHandler->handle(
            new CreateVacancyCommand(
                $languages,
                $names,
                $descriptions,
                $data['skills'] ?? [],
                $data['category_id'],
                $data['organization_id'],
                Auth::id(),
            )
        );

        return new VacancyTranslationsResource($vacancy);
    }

    public function show(VacancyShowRequest $request, Vacancy $vacancy): VacancyTranslationsResource|VacancyResource
    {
        if ($request->with_translations ?? false) {
            return new VacancyTranslationsResource($vacancy);
        }

        return new VacancyResource($vacancy);
    }

    public function update(VacancyUpdateRequest $request, Vacancy $vacancy): VacancyTranslationsResource
    {
        $data = $request->validated();
        $languages = $data['languages'];
        $names = [];
        $descriptions = [];
        foreach ($languages as $language) {
            $names[$language] = $data['name'][$language];
            $descriptions[$language] = $data['description'][$language];
        }
        $vacancy = $this->updateVacancyHandler->handle(
            new UpdateVacancyCommand(
                $vacancy,
                $languages,
                $names,
                $descriptions,
                $data['skills'] ?? [],
                $data['category_id'],
                $data['organization_id'],
                VacancyStatus::from($data['status']),
            )
        );

        return new VacancyTranslationsResource($vacancy);
    }

    public function destroy(Vacancy $vacancy): SuccessResource
    {
        $this->removeVacancyHandler->handle(
            new RemoveVacancyCommand($vacancy)
        );

        return new SuccessResource("Vacancy #$vacancy->id successfully deleted");
    }

    public function index(VacancyCollectionRequest $request): PaginatedVacancyCollectionResource
    {
        $data = $request->validated();
        $page = (int) ($data['page'] ?? 1);
        $filters = new VacancyFiltersDTO(
            isset($data['status']) ? VacancyStatus::from($data['status']) : null,
            isset($data['category_id']) ? (int) $data['category_id'] : null,
            isset($data['organization_id']) ? (int) $data['organization_id'] : null,
            $data['skills'] ?? [],
            $data['text'] ?? null,
        );
        $vacancies = $this->getVacancyCollectionHandler->handle(
            new GetVacancyCollectionCommand(
                $page,
                $filters,
            )
        );

        return new PaginatedVacancyCollectionResource($vacancies);
    }

    public function responses(VacancyResponseCollectionRequest $request, Vacancy $vacancy): PaginatedVacancyResponseCollectionResource
    {
        if ($vacancy->user_id !== Auth::id()) {
            throw new HttpException(403, 'Access denied');
        }

        $data = $request->validated();
        $page = (int) ($data['page'] ?? 1);

        return new PaginatedVacancyResponseCollectionResource(
            $vacancy->responses()->paginate(24, ['*'], 'page', $page)
        );
    }
}
