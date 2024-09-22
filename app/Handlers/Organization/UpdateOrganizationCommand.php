<?php

namespace App\Handlers\Organization;

use App\Models\Organization;

readonly class UpdateOrganizationCommand
{
    /**
     * @param string[] $languages
     * @param array<string, string> $names
     */
    public function __construct(
        public Organization $organization,
        public array $languages,
        public array $names,
    ){
    }
}
