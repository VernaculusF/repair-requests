<?php

declare(strict_types=1);

namespace App\Enums;

enum RequestStatus: string
{
    case New = 'new';
    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case Done = 'done';
    case Canceled = 'canceled';

    public function label(): string
    {
        return match($this) {
            self::New => 'New',
            self::Assigned => 'Assigned',
            self::InProgress => 'In Progress',
            self::Done => 'Done',
            self::Canceled => 'Canceled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::New => 'badge-info',
            self::Assigned => 'badge-warning',
            self::InProgress => 'badge-primary',
            self::Done => 'badge-success',
            self::Canceled => 'badge-danger',
        };
    }
}
