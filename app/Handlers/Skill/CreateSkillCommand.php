<?php

namespace App\Handlers\Skill;

readonly class CreateSkillCommand
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
