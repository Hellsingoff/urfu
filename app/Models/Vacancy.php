<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\VacancyStatus;
use App\Models\Interfaces\Gradable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Vacancy extends TranslatableModel implements Gradable
{
    use HasFactory;

    protected $fillable = [
        'status',
        'category_id',
        'organization_id',
        'user_id',
    ];

    protected $casts = [
        'status' => VacancyStatus::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(VacancyResponse::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'gradable');
    }

    public function rating(): float
    {
        return $this->reviews()->avg('grade');
    }
}
