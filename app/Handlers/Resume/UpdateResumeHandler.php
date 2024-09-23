<?php

declare(strict_types = 1);

namespace App\Handlers\Resume;

use App\Models\Resume;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Uid\Ulid;

readonly class UpdateResumeHandler
{
    public function __construct(
        private Filesystem $filesystem,
    ){
    }

    public function handle(UpdateResumeCommand $command): Resume
    {
        $fields = [];
        if (isset($command->file)) {
            $this->filesystem->delete('resumes/' . $command->resume->filename . '.pdf');
            $filename = (new Ulid())->toString() . '.' . $command->file->extension();
            $this->filesystem->putFileAs('resumes', $command->file, $filename . '.pdf');
            $fields['filename'] = $filename;
        }
        $fields['name'] = $command->name;
        $command->resume->update($fields);

        return $command->resume;
    }
}
