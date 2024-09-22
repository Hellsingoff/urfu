<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property Language $language
 * @property string $attribute
 * @property string $value
 * @method static Builder whereIn(string $column, mixed[] $values)
 */
class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'language',
        'entity_type',
        'entity_id',
        'attribute',
        'value',
    ];

    protected $casts = [
        'language' => Language::class,
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
