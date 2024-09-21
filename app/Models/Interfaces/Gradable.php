<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Gradable
{
    public function reviews(): MorphMany;

    public function rating(): float;
}
