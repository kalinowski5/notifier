<?php
declare(strict_types=1);


namespace App\Enum;


enum NotificationAttemptStatus: string
{
    case SUCCESSFUL = 'successful';
    case FAILED = 'failed';
}
