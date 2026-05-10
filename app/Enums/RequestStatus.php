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
        return match ($this) {
            self::New => 'New',
            self::Assigned => 'Assigned',
            self::InProgress => 'In Progress',
            self::Done => 'Done',
            self::Canceled => 'Canceled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::New => 'badge-info',
            self::Assigned => 'badge-warning',
            self::InProgress => 'badge-primary',
            self::Done => 'badge-success',
            self::Canceled => 'badge-danger',
        };
    }

    public function badgeBackground(): string
    {
        return match ($this) {
            self::New => '#d1ecf1',
            self::Assigned => '#fff3cd',
            self::InProgress => '#d4edff',
            self::Done => '#d4edda',
            self::Canceled => '#f8d7da',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::New => '#0c5460',
            self::Assigned => '#856404',
            self::InProgress => '#004085',
            self::Done => '#155724',
            self::Canceled => '#721c24',
        };
    }
}
