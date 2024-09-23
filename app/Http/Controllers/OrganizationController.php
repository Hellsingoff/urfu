<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Handlers\Organization\CreateOrganizationCommand;
use App\Handlers\Organization\CreateOrganizationHandler;
use App\Handlers\Organization\GetOrganizationCollectionCommand;
use App\Handlers\Organization\GetOrganizationCollectionHandler;
use App\Handlers\Organization\RemoveOrganizationCommand;
use App\Handlers\Organization\RemoveOrganizationHandler;
use App\Handlers\Organization\UpdateOrganizationCommand;
use App\Handlers\Organization\UpdateOrganizationHandler;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Organization\OrganizationCollectionRequest;
use App\Http\Requests\Organization\OrganizationShowRequest;
use App\Http\Requests\Organization\OrganizationUpdateRequest;
use App\Http\Resources\Organization\OrganizationCollectionResource;
use App\Http\Resources\Organization\OrganizationResource;
use App\Http\Resources\Organization\OrganizationTranslationsResource;
use App\Http\Resources\Organization\PaginatedOrganizationCollectionResource;
use App\Http\Resources\SuccessResource;
use App\Models\Organization;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class OrganizationController
{
    public function __construct(
        private CreateOrganizationHandler $createOrganizationHandler,
        private UpdateOrganizationHandler $updateOrganizationHandler,
        private RemoveOrganizationHandler $removeOrganizationHandler,
        private GetOrganizationCollectionHandler $getOrganizationCollectionHandler,
    ){
    }

    public function store(CategoryStoreRequest $request): OrganizationTranslationsResource
    {
        $data = $request->validated();
        $languages = $data['languages'];
        $names = [];
        foreach ($languages as $language) {
            $names[$language] = $data['name'][$language];
        }
        $organization = $this->createOrganizationHandler->handle(
            new CreateOrganizationCommand(
                $languages,
                $names,
            )
        );

        return new OrganizationTranslationsResource($organization);
    }

    public function show(OrganizationShowRequest $request, Organization $organization): OrganizationTranslationsResource|OrganizationResource
    {
        if ($request->with_translations ?? false) {
            return new OrganizationTranslationsResource($organization);
        }

        return new OrganizationResource($organization);
    }

    public function update(OrganizationUpdateRequest $request, Organization $organization): OrganizationTranslationsResource
    {
        $data = $request->validated();
        $languages = $data['languages'];
        $names = [];
        foreach ($languages as $language) {
            $names[$language] = $data['name'][$language];
        }
        $organization = $this->updateOrganizationHandler->handle(
            new UpdateOrganizationCommand(
                $organization,
                $languages,
                $names,
            )
        );

        return new OrganizationTranslationsResource($organization);
    }

    public function destroy(Organization $organization): SuccessResource
    {
        $this->removeOrganizationHandler->handle(
            new RemoveOrganizationCommand($organization)
        );

        return new SuccessResource("Organization #$organization->id successfully deleted");
    }

    public function index(OrganizationCollectionRequest $request): OrganizationCollectionResource|PaginatedOrganizationCollectionResource
    {
        $data = $request->validated();
        $withoutPagination = isset($data['without_pagination']) && (boolean) $data['without_pagination'];
        $page = $withoutPagination ? null : (int) $data['page'] ?? 1;
        $organizations = $this->getOrganizationCollectionHandler->handle(
            new GetOrganizationCollectionCommand(
                $withoutPagination,
                $page
            )
        );

        if ($organizations instanceof LengthAwarePaginator) {
            return new PaginatedOrganizationCollectionResource($organizations);
        }

        return new OrganizationCollectionResource($organizations);
    }
}
