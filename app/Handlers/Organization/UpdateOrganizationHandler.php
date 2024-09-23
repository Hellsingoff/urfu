<?php

declare(strict_types = 1);

namespace App\Handlers\Organization;

use App\Models\Organization;

readonly class UpdateOrganizationHandler
{
    public function handle(UpdateOrganizationCommand $command): Organization
    {
        $command->organization->fields()->delete();

        $fields = [];
        foreach ($command->languages as $language) {
            $fields[] = [
                'language' => $language,
                'attribute' => 'name',
                'value' => $command->names[$language]
            ];
        }

        $command->organization->fields()->createMany($fields);

        return $command->organization;
    }
}
