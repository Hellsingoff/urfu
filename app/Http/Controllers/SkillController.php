<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Handlers\Skill\CreateSkillCommand;
use App\Handlers\Skill\CreateSkillHandler;
use App\Handlers\Skill\GetSkillCollectionCommand;
use App\Handlers\Skill\GetSkillCollectionHandler;
use App\Handlers\Skill\RemoveSkillCommand;
use App\Handlers\Skill\RemoveSkillHandler;
use App\Handlers\Skill\UpdateSkillCommand;
use App\Handlers\Skill\UpdateSkillHandler;
use App\Http\Requests\Skill\SkillCollectionRequest;
use App\Http\Requests\Skill\SkillShowRequest;
use App\Http\Requests\Skill\SkillStoreRequest;
use App\Http\Requests\Skill\SkillUpdateRequest;
use App\Http\Resources\Skill\PaginatedSkillCollectionResource;
use App\Http\Resources\Skill\SkillCollectionResource;
use App\Http\Resources\Skill\SkillResource;
use App\Http\Resources\Skill\SkillTranslationsResource;
use App\Http\Resources\SuccessResource;
use App\Models\Skill;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class SkillController
{
    public function __construct(
        private CreateSkillHandler $createSkillHandler,
        private UpdateSkillHandler $updateSkillHandler,
        private RemoveSkillHandler $removeSkillHandler,
        private GetSkillCollectionHandler $getSkillCollectionHandler,
    ){
    }

    public function store(SkillStoreRequest $request): SkillTranslationsResource
    {
        $data = $request->validated();
        $languages = $data['languages'];
        $names = [];
        foreach ($languages as $language) {
            $names[$language] = $data['name'][$language];
        }
        $skill = $this->createSkillHandler->handle(
            new CreateSkillCommand(
                $languages,
                $names,
            )
        );

        return new SkillTranslationsResource($skill);
    }

    public function show(SkillShowRequest $request, Skill $skill): SkillTranslationsResource|SkillResource
    {
        if ($request->with_translations ?? false) {
            return new SkillTranslationsResource($skill);
        }

        return new SkillResource($skill);
    }

    public function update(SkillUpdateRequest $request, Skill $skill): SkillTranslationsResource
    {
        $data = $request->validated();
        $languages = $data['languages'];
        $names = [];
        foreach ($languages as $language) {
            $names[$language] = $data['name'][$language];
        }
        $skill = $this->updateSkillHandler->handle(
            new UpdateSkillCommand(
                $skill,
                $languages,
                $names,
            )
        );

        return new SkillTranslationsResource($skill);
    }

    public function destroy(Skill $skill): SuccessResource
    {
        $this->removeSkillHandler->handle(
            new RemoveSkillCommand($skill)
        );

        return new SuccessResource("Skill #$skill->id successfully deleted");
    }

    public function index(SkillCollectionRequest $request): SkillCollectionResource|PaginatedSkillCollectionResource
    {
        $data = $request->validated();
        $withoutPagination = isset($data['without_pagination']) && (boolean) $data['without_pagination'];
        $page = $withoutPagination ? null : (int) ($data['page'] ?? 1);
        $skills = $this->getSkillCollectionHandler->handle(
            new GetSkillCollectionCommand(
                $withoutPagination,
                $page
            )
        );

        if ($skills instanceof LengthAwarePaginator) {
            return new PaginatedSkillCollectionResource($skills);
        }

        return new SkillCollectionResource($skills);
    }
}
