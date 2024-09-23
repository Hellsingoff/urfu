<?php

declare(strict_types=1);

namespace App\Handlers\VacancyResponse;

use App\Enum\VacancyResponseStatus;
use App\Models\Commentary;
use App\Models\VacancyResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

readonly class CreateVacancyResponseHandler
{
    /**
     * @throws HttpException
     */
    public function handle(CreateVacancyResponseCommand $command): VacancyResponse
    {
        $responseExists = VacancyResponse::where([
            'user_id' => $command->userId,
            'vacancy_id' => $command->vacancyId,
        ])->exists();
        if ($responseExists) {
            throw new HttpException(403, 'Vacancy already created.');
        }
        $response = VacancyResponse::create([
            'user_id' => $command->userId,
            'vacancy_id' => $command->vacancyId,
            'resume_id' => $command->resumeId,
            'status' => VacancyResponseStatus::New,
            'must_notify_vacancy_owner' => true
        ]);

        if (isset($command->commentary)) {
            Commentary::create([
                'user_id' => $command->userId,
                'vacancy_response_id' => $response->id,
                'text' => $command->commentary
            ]);
        }

        return $response;
    }
}
