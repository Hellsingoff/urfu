<?php

declare(strict_types=1);

namespace App\Handlers\Skill;

class RemoveSkillHandler
{
    public function handle(RemoveSkillCommand $command): void
    {
        $command->skill->fields()->delete();
        $command->skill->delete();
    }
}
