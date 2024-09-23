<?php

declare(strict_types=1);

namespace App\Handlers\VacancyResponse;

use App\Models\VacancyResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

readonly class UpdateVacancyResponseHandler
{
    /**
     * @throws HttpException
     */
    public function handle(UpdateVacancyResponseCommand $command): VacancyResponse
    {
        $statusChanged = $command->vacancyResponse->status !== $command->status;
        if ($statusChanged) {
            $command->vacancyResponse->update([
                'status' => $command->status,
                'must_notify_response_owner' => true,
            ]);
        }

        return $command->vacancyResponse;
    }
}
