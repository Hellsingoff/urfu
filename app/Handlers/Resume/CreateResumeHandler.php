<?php

declare(strict_types=1);

namespace App\Handlers\Resume;

use App\Models\Resume;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Uid\Ulid;

readonly class CreateResumeHandler
{
    public function __construct(
        private Filesystem $filesystem,
    ){
    }

    public function handle(CreateResumeCommand $command): Resume
    {
        $filename = (new Ulid())->toString();
        $this->filesystem->putFileAs('resumes', $command->file, $filename . '.pdf');

        return Resume::create([
            'name' => $command->name,
            'filename' => $filename,
            'user_id' => $command->userId
        ]);
    }
}
