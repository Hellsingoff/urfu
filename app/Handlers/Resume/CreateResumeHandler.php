<?php

declare(strict_types=1);

namespace App\Handlers\Resume;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Uid\Ulid;

readonly class CreateResumeHandler
{
    public function __construct(
        private Filesystem $filesystem,
    ){
    }

    public function handle(CreateResumeCommand $command): Resume
    {
        /** @var User $user */
        $user = Auth::user();
        if (9 < $user->resume()->count()) {
            throw new HttpException(403, 'You cannot add more than 10 resumes.');
        }
        $filename = (new Ulid())->toString();
        $this->filesystem->putFileAs('resumes', $command->file, $filename . '.pdf');

        return Resume::create([
            'name' => $command->name,
            'filename' => $filename,
            'user_id' => $user->id,
        ]);
    }
}
