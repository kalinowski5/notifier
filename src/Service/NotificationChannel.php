<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Notification;

interface NotificationChannel
{
    public function sendNotification(Notification $notification): void;
}
