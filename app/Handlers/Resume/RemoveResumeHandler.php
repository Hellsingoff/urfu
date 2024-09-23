<?php

declare(strict_types=1);

namespace App\Handlers\Resume;

use Illuminate\Contracts\Filesystem\Filesystem;

readonly class RemoveResumeHandler
{
    public function __construct(
        private Filesystem $filesystem,
    ){
    }

    public function handle(RemoveResumeCommand $command): void
    {
        $this->filesystem->delete('resumes/' . $command->resume->filename . '.pdf');
        $command->resume->delete();
    }
}
