<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enum\VacancyResponseStatus;
use App\Handlers\VacancyResponse\CancelVacancyResponseCommand;
use App\Handlers\VacancyResponse\CancelVacancyResponseHandler;
use App\Handlers\VacancyResponse\CreateVacancyResponseCommand;
use App\Handlers\VacancyResponse\CreateVacancyResponseCommentaryCommand;
use App\Handlers\VacancyResponse\CreateVacancyResponseCommentaryHandler;
use App\Handlers\VacancyResponse\CreateVacancyResponseHandler;
use App\Handlers\VacancyResponse\UpdateVacancyResponseCommand;
use App\Handlers\VacancyResponse\UpdateVacancyResponseHandler;
use App\Http\Requests\VacancyResponse\VacancyResponseCommentaryStoreRequest;
use App\Http\Requests\VacancyResponse\VacancyResponseStoreRequest;
use App\Http\Requests\VacancyResponse\VacancyResponseUpdateRequest;
use App\Http\Resources\Commentary\CommentaryCollectionResource;
use App\Http\Resources\VacancyResponse\VacancyResponseResource;
use App\Models\Resume;
use App\Models\VacancyResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

readonly class VacancyResponseController
{
    public function __construct(
        private CreateVacancyResponseHandler $createVacancyResponseHandler,
        private UpdateVacancyResponseHandler $updateVacancyResponseHandler,
        private CreateVacancyResponseCommentaryHandler $createVacancyResponseCommentaryHandler,
        private CancelVacancyResponseHandler $cancelVacancyResponseHandler,
    ){
    }

    /**
     * @throws HttpException
     */
    public function store(VacancyResponseStoreRequest $request): VacancyResponseResource
    {
        $data = $request->validated();
        if (Resume::find($data['resume_id'])->user_id !== Auth::id()) {
            throw new HttpException(403, __('messages.forbidden'));
        }
        $response = $this->createVacancyResponseHandler->handle(
            new CreateVacancyResponseCommand(
                (int) $data['vacancy_id'],
                (int) $data['resume_id'],
                Auth::id(),
                $data['commentary'] ?? null,
            )
        );

        return new VacancyResponseResource($response);
    }

    public function update(VacancyResponseUpdateRequest $request, VacancyResponse $vacancyResponse): VacancyResponseResource
    {
        if ($vacancyResponse->vacancy->user_id !== Auth::id()) {
            throw new HttpException(403, __('messages.forbidden'));
        }
        $data = $request->validated();
        $response = $this->updateVacancyResponseHandler->handle(
            new UpdateVacancyResponseCommand(
                $vacancyResponse,
                VacancyResponseStatus::from($data['status'])
            )
        );

        return new VacancyResponseResource($response);
    }

    public function show(VacancyResponse $vacancyResponse): VacancyResponseResource
    {
        if (
            $vacancyResponse->vacancy->user_id !== Auth::id()
            || $vacancyResponse->user_id !== Auth::id()
        ) {
            throw new HttpException(403, __('messages.forbidden'));
        }

        return new VacancyResponseResource($vacancyResponse);
    }

    public function commentaries(VacancyResponse $vacancyResponse): CommentaryCollectionResource
    {
        if (
            $vacancyResponse->vacancy->user_id !== Auth::id()
            || $vacancyResponse->user_id !== Auth::id()
        ) {
            throw new HttpException(403, __('messages.forbidden'));
        }

        return new CommentaryCollectionResource($vacancyResponse->commentaries);
    }

    public function storeCommentary(VacancyResponseCommentaryStoreRequest $request, VacancyResponse $vacancyResponse): CommentaryCollectionResource
    {
        if (
            $vacancyResponse->vacancy->user_id !== Auth::id()
            || $vacancyResponse->user_id !== Auth::id()
        ) {
            throw new HttpException(403, __('messages.forbidden'));
        }
        $data = $request->validated();

        $this->createVacancyResponseCommentaryHandler->handle(
            new CreateVacancyResponseCommentaryCommand(
                $vacancyResponse,
                $data['commentary'],
                Auth::id(),
                $vacancyResponse->user_id === Auth::id()
            )
        );

        return new CommentaryCollectionResource($vacancyResponse->commentaries);
    }

    public function cancel(VacancyResponse $vacancyResponse): VacancyResponseResource
    {
        if ($vacancyResponse->user_id !== Auth::id()) {
            throw new HttpException(403, __('messages.forbidden'));
        }

        $response = $this->cancelVacancyResponseHandler->handle(
            new CancelVacancyResponseCommand(
                $vacancyResponse
            )
        );

        return new VacancyResponseResource($response);
    }
}
