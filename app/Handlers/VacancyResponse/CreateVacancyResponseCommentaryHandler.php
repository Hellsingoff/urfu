<?php

declare(strict_types=1);

namespace App\Handlers\VacancyResponse;

use App\Models\Commentary;

readonly class CreateVacancyResponseCommentaryHandler
{
    public function handle(CreateVacancyResponseCommentaryCommand $command): Commentary
    {
        $command->vacancyResponse->update([
            'must_notify_response_owner' => false === $command->mustNotifyVacancyOwner,
            'must_notify_vacancy_owner' => $command->mustNotifyVacancyOwner,
        ]);

        return Commentary::create([
            'user_id' => $command->userId,
            'vacancy_response_id' => $command->vacancyResponse->id,
            'text' => $command->commentary
        ]);
    }
}
