<?php

namespace App\Handlers\Skill;

use App\Models\Skill;

readonly class UpdateSkillCommand
{
    /**
     * @param string[] $languages
     * @param array<string, string> $names
     */
    public function __construct(
        public Skill $skill,
        public array $languages,
        public array $names,
    ){
    }
}
