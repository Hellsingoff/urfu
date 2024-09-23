<?php

declare(strict_types = 1);

namespace App\Handlers\Skill;

use App\Models\Skill;

readonly class UpdateSkillHandler
{
    public function handle(UpdateSkillCommand $command): Skill
    {
        $command->skill->fields()->delete();

        $fields = [];
        foreach ($command->languages as $language) {
            $fields[] = [
                'language' => $language,
                'attribute' => 'name',
                'value' => $command->names[$language]
            ];
        }

        $command->skill->fields()->createMany($fields);

        return $command->skill;
    }
}
