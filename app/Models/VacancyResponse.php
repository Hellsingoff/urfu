<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\VacancyResponseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VacancyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'user_id',
        'vacancy_id',
        'resume_id',
        'must_notify_vacancy_owner',
        'must_notify_response_owner',
    ];

    protected $casts = [
        'status' => VacancyResponseStatus::class,
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function commentaries(): HasMany
    {
        return $this->hasMany(Commentary::class);
    }
}
