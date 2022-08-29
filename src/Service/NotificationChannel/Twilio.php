<?php
declare(strict_types=1);


namespace App\Service\NotificationChannel;

use App\Model\Notification;
use App\Repository\CustomerRepository;
use App\Service\NotificationChannel;
use Twilio\Rest\Client;

final class Twilio implements NotificationChannel //@TODO: Test me
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
    )
    {

    }

    public function sendNotification(Notification $notification): void
    {
        $customer = $this->customerRepository->findById($notification->customerId());

        if (!$customer) {
            return;
        }

        $accountSid = 'ACXXXXXXXXXXXXXXXXXXXXXXXXXXXX'; //@TODO: Use DI
        $authToken = 'your_auth_token'; //@TODO: Use DI
        // In production, these should be environment variables. E.g.:
        // $authToken = $_ENV["TWILIO_AUTH_TOKEN"]

        // A Twilio number you own with SMS capabilities
        $twilioNumber = "+15017122661"; //@TODO: Use DI

        $client = new Client($accountSid, $authToken);
        $client->messages->create(
            (string) $customer->phoneNumber(),
            [
                'from' => $twilioNumber,
                'body' => $notification->message(),
            ]
        );
    }
}
