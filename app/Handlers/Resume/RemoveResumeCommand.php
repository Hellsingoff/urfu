<?php

namespace App\Handlers\Resume;

use App\Models\Resume;

readonly class RemoveResumeCommand
{
    public function __construct(
        public Resume $resume,
    ){
    }
}
