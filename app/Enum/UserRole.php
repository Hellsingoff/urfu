<?php

declare(strict_types=1);

namespace App\Enum;

enum UserRole: string
{
    private const TRANSLATIONS = [
        Language::Russian->value => [
            self::User->value => 'Студент',
            self::Moderator->value => 'Сотрудник',
        ],
        Language::English->value => [
            self::User->value => 'Student',
            self::Moderator->value => 'Employee',
        ],
        Language::Deutsch->value => [
            self::User->value => 'Student',
            self::Moderator->value => 'Mitarbeiter',
        ]
    ];

    case User = 'user';
    case Moderator = 'moderator';

    public function label(Language $language): string
    {
        return self::TRANSLATIONS[$language->value][$this->value] ?? $this->value;
    }
}
