<?php
declare(strict_types=1);


namespace App\Service\NotificationChannel;

use App\Model\Notification;
use App\Repository\CustomerRepository;
use App\Service\NotificationChannel;
use Twilio\Rest\Client;

final class Twilio implements NotificationChannel
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly Client $twilioClient,
        private readonly string $twilioNumber,
    ) {
    }

    public function sendNotification(Notification $notification): void
    {
        $customer = $this->customerRepository->getById($notification->customerId());

        $this->twilioClient->messages->create(
            (string) $customer->phoneNumber(),
            [
                'from' => $this->twilioNumber,
                'body' => $notification->message(),
            ]
        );
    }
}
