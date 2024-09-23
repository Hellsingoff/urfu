<?php

declare(strict_types=1);

namespace App\Handlers\VacancyResponse;

use App\Enum\VacancyResponseStatus;
use App\Models\VacancyResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

readonly class CancelVacancyResponseHandler
{
    /**
     * @throws HttpException
     */
    public function handle(CancelVacancyResponseCommand $command): VacancyResponse
    {
        $statusChanged = $command->vacancyResponse->status !== VacancyResponseStatus::Cancelled;
        if ($statusChanged) {
            $command->vacancyResponse->update([
                'status' => VacancyResponseStatus::Cancelled,
                'must_notify_vacancy_owner' => true,
            ]);
        }

        return $command->vacancyResponse;
    }
}
