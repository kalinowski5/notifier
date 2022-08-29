<?php
declare(strict_types=1);


namespace App\Service\NotificationChannel;


use App\Model\Notification;
use App\Repository\CustomerRepository;
use App\Service\NotificationChannel;
use Aws\Exception\AwsException;
use Aws\Ses\SesClient;

final class AmazonSES implements NotificationChannel //@TODO: Test me
{
    public function __construct(
//        private readonly CustomerRepository $customerRepository,
    )
    {

    }

    public function sendNotification(Notification $notification): void
    {


        $recipientEmail = 'test@domain.com'; //@TODO: From repo

        $templateName = 'Template_Name'; //@TODO: create template in AWS console
        $senderEmail = 'email_address';
        $amazonSesClientProfile = 'default';
        $amazonSesClientVersion = '2010-12-01';
        $amazonSesClientRegion = 'us-east-2';

        $client = new SesClient([
            'profile' => $amazonSesClientProfile,
            'version' => $amazonSesClientVersion,
            'region' => $amazonSesClientRegion,
        ]);

        try {
            $result = $client->sendTemplatedEmail([
                'Destination' => [
                    'ToAddresses' => [$recipientEmail],
                ],
                'ReplyToAddresses' => [$senderEmail],
                'Source' => $senderEmail,

                'Template' => $templateName,
                'TemplateData' => '{ }' //@TODO: message
            ]);
            dump($result);
        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage();
            echo "\n";
        }
    }
}
