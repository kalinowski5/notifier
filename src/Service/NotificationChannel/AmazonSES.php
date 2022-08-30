<?php
declare(strict_types=1);

namespace App\Service\NotificationChannel;

use App\Model\Notification;
use App\Repository\CustomerRepository;
use App\Service\NotificationChannel;
use Aws\Ses\SesClient;

final class AmazonSES implements NotificationChannel
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly SesClient $sesClient,
        private readonly string $senderEmail,
    ) {
    }

    public function sendNotification(Notification $notification): void
    {
        $customer = $this->customerRepository->getById($notification->customerId());

        $recipientEmail = $customer->email();

        $htmlBody = sprintf('<p>%s</p>', $notification->message());
        $subject = $notification->subject();
        $plaintextBody = $notification->message();
        $charset = 'UTF-8';

        $this->sesClient->sendEmail([
            'Destination' => [
                'ToAddresses' => [(string)$recipientEmail],
            ],
            'ReplyToAddresses' => [$this->senderEmail],
            'Source' => $this->senderEmail,
            'Message' => [
                'Body' => [
                    'Html' => [
                        'Charset' => $charset,
                        'Data' => $htmlBody,
                    ],
                    'Text' => [
                        'Charset' => $charset,
                        'Data' => $plaintextBody,
                    ],
                ],
                'Subject' => [
                    'Charset' => $charset,
                    'Data' => $subject,
                ],
            ],
        ]);
    }
}
