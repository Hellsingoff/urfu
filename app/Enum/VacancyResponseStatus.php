<?php

declare(strict_types=1);

namespace App\Enum;

enum VacancyResponseStatus: string
{
    private const TRANSLATIONS = [
        Language::Russian->value => [
            self::New->value => 'Новый',
            self::InProcess->value => 'В процессе',
            self::Rejected->value => 'Отклонен',
            self::Approved->value => 'Одобрен',
            self::Cancelled->value => 'Отменен',
        ],
        Language::English->value => [
            self::New->value => 'New',
            self::InProcess->value => 'In Process',
            self::Rejected->value => 'Rejected',
            self::Approved->value => 'Approved',
            self::Cancelled->value => 'Cancelled',
        ],
        Language::Deutsch->value => [
            self::New->value => 'Neu',
            self::InProcess->value => 'In Bearbeitung',
            self::Rejected->value => 'Abgelehnt',
            self::Approved->value => 'Genehmigt',
            self::Cancelled->value => 'Abgesagt',
        ],
    ];

    case New = 'new';
    case InProcess = 'in-process';
    case Rejected = 'rejected';
    case Approved = 'approved';
    case Cancelled = 'cancelled';

    public function label(Language $language): string
    {
        return self::TRANSLATIONS[$language->value][$this->value] ?? $this->value;
    }
}
