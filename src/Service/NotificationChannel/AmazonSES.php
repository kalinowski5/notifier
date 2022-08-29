<?php
declare(strict_types=1);

namespace App\Service\NotificationChannel;

use App\Model\Notification;
use App\Repository\CustomerRepository;
use App\Service\NotificationChannel;
use Aws\Ses\SesClient;

final class AmazonSES implements NotificationChannel //@TODO: Test me
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
    )
    {

    }

    public function sendNotification(Notification $notification): void
    {
        $customer = $this->customerRepository->getById($notification->customerId());

        $recipientEmail = $customer->email();

        $senderEmail = 'kalinowski5@gmail.com'; //@TODO: env
        $amazonSesClientProfile = 'default';
        $amazonSesClientVersion = '2010-12-01';
        $amazonSesClientRegion = 'eu-north-1';//@TODO: env

        $client = new SesClient([
            'profile' => $amazonSesClientProfile,
            'version' => $amazonSesClientVersion,
            'region' => $amazonSesClientRegion,
        ]);

        $htmlBody = sprintf('<p>%s</p>', $notification->message());
        $subject = $notification->subject();
        $plaintextBody = $notification->message();
        $charset = 'UTF-8';

        $result = $client->sendEmail([
            'Destination' => [
                'ToAddresses' => [$recipientEmail],
            ],
            'ReplyToAddresses' => [$senderEmail],
            'Source' => $senderEmail,
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
        dump($result);

    }
}
