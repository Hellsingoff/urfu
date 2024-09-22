<?php

namespace App\Handlers\Skill;

use App\Models\Skill;

readonly class RemoveSkillCommand
{
    public function __construct(
        public Skill $skill,
    ){
    }
}
