<?php
declare(strict_types=1);


namespace App\Service\NotificationChannel;


use App\Model\Notification;
use App\Service\NotificationChannel;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Pushy implements NotificationChannel //@TODO: Test me
{
    public function __construct(
        private readonly HttpClientInterface $httpClient //@TODO: Inject named client?
    )
    {

    }

    public function sendNotification(Notification $notification): void
    {
        $deviceToken = 'a6345d0278adc55d3474f5'; //@TODO: Take from customer
        $apiKey = 'SECRET_API_KEY'; //@TODO: Inject

        $endpointUrl = sprintf("https://api.pushy.me/push?api_key=%s", $apiKey);

        $this->httpClient->request('POST', $endpointUrl, [
            'headers' => [
                'Content-type' => 'application/json',
            ],
            'body' => json_encode([
                'to' => $deviceToken, //Please note: it can be extended by supporting multiple customer's devices
                'data' => [
                    'message' => $notification->message(),
                ]
            ]),
        ]);
    }
}
