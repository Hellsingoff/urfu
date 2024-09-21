<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends TranslatableModel
{
    use HasFactory;

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }
}
