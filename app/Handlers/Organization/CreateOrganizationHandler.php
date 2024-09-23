<?php

declare(strict_types=1);

namespace App\Handlers\Organization;

use App\Models\Organization;

readonly class CreateOrganizationHandler
{
    public function handle(CreateOrganizationCommand $command): Organization
    {
        $organization = Organization::create();
        $fields = [];
        foreach ($command->languages as $language) {
            $fields[] = [
                'language' => $language,
                'attribute' => 'name',
                'value' => $command->names[$language]
            ];
        }

        $organization->fields()->createMany($fields);

        return $organization;
    }
}
