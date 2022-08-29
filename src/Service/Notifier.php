<?php
declare(strict_types=1);

namespace App\Service;

use App\Enum\DeliveryPolicy;
use App\Model\Notification;

final class Notifier
{
    private readonly DeliveryPolicy $deliveryPolicy;

    /**
     * @param iterable<NotificationChannel> $notificationChannels
     */
    public function __construct(
        private readonly iterable $notificationChannels,
        string $deliveryPolicy,
        //@TODO: sending history logger (DB)
    )
    {
        $this->deliveryPolicy = DeliveryPolicy::from($deliveryPolicy);
    }

    public function sendNotification(Notification $notification): void
    {
        foreach ($this->notificationChannels as $notificationChannel) {

            try {
                $notificationChannel->sendNotification($notification);
                //@TODO: Log

                if (DeliveryPolicy::FIRST_WORKING_CHANNEL === $this->deliveryPolicy) {
                    return;
                }
            } catch (\Exception) {
                //@TODO: Log problem
            }
        }
    }
}
