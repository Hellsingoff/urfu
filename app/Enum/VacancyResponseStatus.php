<?php

declare(strict_types=1);

namespace App\Enum;

enum VacancyResponseStatus: string
{
    case New = 'new';
    case InProcess = 'in-process';
    case Rejected = 'rejected';
    case Approved = 'approved';
}
