<?php
declare(strict_types=1);

namespace App\Service\NotificationChannel;

use App\Model\Notification;
use App\Service\NotificationChannel;

final class AlwaysBroken implements NotificationChannel
{
    public function sendNotification(Notification $notification): void
    {
        throw new \Exception("I'm purposely broken... Do not use me in production!");
    }
}
