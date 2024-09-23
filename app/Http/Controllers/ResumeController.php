<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Handlers\Resume\CreateResumeCommand;
use App\Handlers\Resume\CreateResumeHandler;
use App\Handlers\Resume\RemoveResumeCommand;
use App\Handlers\Resume\RemoveResumeHandler;
use App\Handlers\Resume\UpdateResumeCommand;
use App\Handlers\Resume\UpdateResumeHandler;
use App\Http\Requests\Resume\ResumeStoreRequest;
use App\Http\Requests\Resume\ResumeUpdateRequest;
use App\Http\Resources\Resume\ResumeResource;
use App\Http\Resources\SuccessResource;
use App\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

readonly class ResumeController
{
    public function __construct(
        private CreateResumeHandler $createResumeHandler,
        private UpdateResumeHandler $updateResumeHandler,
        private RemoveResumeHandler $removeResumeHandler,
    ){
    }

    public function store(ResumeStoreRequest $request): ResumeResource
    {
        $data = $request->validated();
        $resume = $this->createResumeHandler->handle(
            new CreateResumeCommand(
                $data['file'],
                $data['name'],
                Auth::id(),
            )
        );

        return new ResumeResource($resume);
    }

    public function update(ResumeUpdateRequest $request, Resume $resume): ResumeResource
    {
        if (Auth::id() !== $resume->user_id) {
            throw new HttpException(403, 'Forbidden');
        }
        $data = $request->validated();
        $resume = $this->updateResumeHandler->handle(
            new UpdateResumeCommand(
                $resume,
                $data['name'],
                $data['file'] ?? null,
            )
        );

        return new ResumeResource($resume);
    }

    public function destroy(Resume $resume): SuccessResource
    {
        if (Auth::id() !== $resume->user_id) {
            throw new HttpException(403, 'Forbidden');
        }
        $this->removeResumeHandler->handle(
            new RemoveResumeCommand($resume)
        );

        return new SuccessResource("Resume #$resume->id successfully deleted");
    }
}
