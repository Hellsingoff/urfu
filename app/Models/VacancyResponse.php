<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\VacancyResponseStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property-read User $owner
 * @property-read int $user_id
 * @property-read VacancyResponseStatus $status
 * @property-read Resume $resume
 * @property-read Vacancy $vacancy
 * @property-read Collection<int, Commentary> $commentaries
 * @method static self create(array $params = [])
 * @method static Builder where(array $params = [])
 */
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
        return $this->belongsTo(User::class, 'user_id');
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
        return $this->hasMany(Commentary::class)->orderBy('created_at');
    }
}
