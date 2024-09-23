<?php

namespace App\Handlers\Resume;

use App\Models\Resume;
use Illuminate\Http\UploadedFile;

readonly class UpdateResumeCommand
{
    public function __construct(
        public Resume $resume,
        public string $name,
        public ?UploadedFile $file,
    ){
    }
}
