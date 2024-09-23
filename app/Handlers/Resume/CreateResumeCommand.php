<?php

namespace App\Handlers\Resume;

use Illuminate\Http\UploadedFile;

readonly class CreateResumeCommand
{
    public function __construct(
        public UploadedFile $file,
        public string $name,
    ){
    }
}
