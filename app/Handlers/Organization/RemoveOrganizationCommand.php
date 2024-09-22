<?php

namespace App\Handlers\Organization;

use App\Models\Organization;

readonly class RemoveOrganizationCommand
{
    public function __construct(
        public Organization $organization,
    ){
    }
}
