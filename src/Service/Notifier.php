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
        private readonly NotificationLogger $logger,
    )
    {
        $this->deliveryPolicy = DeliveryPolicy::from($deliveryPolicy);
    }

    public function sendNotification(Notification $notification): void
    {
        foreach ($this->notificationChannels as $notificationChannel) {

            try {
                $notificationChannel->sendNotification($notification);

                $this->logger->logSuccess($notification->customerId(), $notificationChannel::class);

                if (DeliveryPolicy::FIRST_WORKING_CHANNEL === $this->deliveryPolicy) {
                    return;
                }
            } catch (\Exception $exception) {
                dump($exception->getMessage()); //@TODO: tmp
                $this->logger->logFailure($notification->customerId(), $notificationChannel::class, $exception->getMessage());
            }
        }
    }
}
