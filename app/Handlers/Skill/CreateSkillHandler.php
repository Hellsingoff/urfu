<?php

declare(strict_types=1);

namespace App\Handlers\Skill;

use App\Models\Skill;

readonly class CreateSkillHandler
{
    public function handle(CreateSkillCommand $command): Skill
    {
        $skill = Skill::create();
        $fields = [];
        foreach ($command->languages as $language) {
            $fields[] = [
                'language' => $language,
                'attribute' => 'name',
                'value' => $command->names[$language]
            ];
        }

        $skill->fields()->createMany($fields);

        return $skill;
    }
}
