<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read User $owner
 * @property-read string $text
 * @property-read Carbon $created_at
 * @method static self create(array $params = [])
 */
class Commentary extends Model
{
    use HasFactory;

    protected $fillable = [
        'vacancy_response_id',
        'user_id',
        'text',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vacancyResponse(): BelongsTo
    {
        return $this->belongsTo(VacancyResponse::class);
    }
}
