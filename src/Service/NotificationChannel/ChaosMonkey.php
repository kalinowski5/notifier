<?php
declare(strict_types=1);

namespace App\Service\NotificationChannel;

use App\Model\Notification;
use App\Service\NotificationChannel;

final class ChaosMonkey implements NotificationChannel
{
    private const EXPECTED_UPTIME = 50; //On average, on every 2nd attempt, channel won't be operating properly

    public function sendNotification(Notification $notification): void
    {
        if (rand(0, 100) < self::EXPECTED_UPTIME) {
            throw new \Exception("I'm chaos monkey 🐒, who randomly breaks this notification channel.");
        }
    }
}
