<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(User::class);
    }

    public function vacancyResponse(): BelongsTo
    {
        return $this->belongsTo(VacancyResponse::class);
    }
}
