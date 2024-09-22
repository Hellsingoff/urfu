<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read int $id
 * @method static self create(array $params = [])
 */
class Skill extends TranslatableModel
{
    use HasFactory;

    public function resumes(): BelongsToMany
    {
        return $this->belongsToMany(Resume::class);
    }

    public function vacancies(): BelongsToMany
    {
        return $this->belongsToMany(Vacancy::class);
    }
}
