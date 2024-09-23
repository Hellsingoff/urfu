<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\UserRole;
use App\Models\Interfaces\Gradable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read int $id
 * @property string $name
 * @property UserRole $role
 * @property-read Collection<int, Resume> $resume
 * @property-read Collection<int, VacancyResponse> $responses
 * @method static self create(array $params)
 */
class User extends Authenticatable implements Gradable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function resume(): HasMany
    {
        return $this->hasMany(Resume::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'gradable');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(VacancyResponse::class)->orderBy('updated_at', 'desc');
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class)->orderBy('updated_at', 'desc');
    }

    public function rating(): ?float
    {
        return $this->reviews()->avg('grade');
    }
}
