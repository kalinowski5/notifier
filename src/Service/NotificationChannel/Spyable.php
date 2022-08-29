<?php
declare(strict_types=1);

namespace App\Service\NotificationChannel;

use App\Model\Notification;
use App\Service\NotificationChannel;

/**
 * Use only for test purposes
 */
final class Spyable implements NotificationChannel
{
    /**
     * @var Notification[]
     */
    private array $notificationsSent = [];

    public function sendNotification(Notification $notification): void
    {
        $this->notificationsSent[] = $notification;
    }

    /**
     * @return Notification[]
     */
    public function notificationsSent(): array
    {
        return $this->notificationsSent;
    }
}
