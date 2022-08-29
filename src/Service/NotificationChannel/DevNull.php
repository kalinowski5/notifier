<?php
declare(strict_types=1);

namespace App\Service\NotificationChannel;

use App\Model\Notification;
use App\Service\NotificationChannel;

final class DevNull implements NotificationChannel
{
    public function sendNotification(Notification $notification): void
    {
        //All notifications just go to "/dev/null" :)
        //It's not advised to use it in production
    }
}
