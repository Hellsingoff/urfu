<?php

declare(strict_types=1);

namespace App\Enum;

enum VacancyStatus: string
{
    private const TRANSLATIONS = [
        Language::Russian->value => [
            self::Open->value => 'Открыта',
            self::Closed->value => 'Закрыта',
        ],
        Language::English->value => [
            self::Open->value => 'Open',
            self::Closed->value => 'Closed',
        ],
        Language::Deutsch->value => [
            self::Open->value => 'Offen',
            self::Closed->value => 'Geschlossen',
        ],
    ];

    case Open = 'open';
    case Closed = 'closed';

    public function label(Language $language): string
    {
        return self::TRANSLATIONS[$language->value][$this->value] ?? $this->value;
    }
}
