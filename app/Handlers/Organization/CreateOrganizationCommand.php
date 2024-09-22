<?php

namespace App\Handlers\Organization;

readonly class CreateOrganizationCommand
{
    /**
     * @param string[] $languages
     * @param array<string, string> $names
     */
    public function __construct(
        public array $languages,
        public array $names,
    ){
    }
}
