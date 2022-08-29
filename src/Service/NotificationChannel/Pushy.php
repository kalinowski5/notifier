<?php
declare(strict_types=1);

namespace App\Service\NotificationChannel;

use App\Model\Notification;
use App\Repository\CustomerRepository;
use App\Service\NotificationChannel;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Pushy implements NotificationChannel
{
    private const CONTENT_TYPE = 'application/json';

    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly HttpClientInterface $httpClient,
        private readonly string $apiKey,
    ) {
    }

    public function sendNotification(Notification $notification): void
    {
        $deviceToken = $this->getDeviceToken($notification);

        $endpointUrl = sprintf("https://api.pushy.me/push?api_key=%s", $this->apiKey);

        $response = $this->httpClient->request('POST', $endpointUrl, [
            'headers' => [
                'Content-type' => self::CONTENT_TYPE,
            ],
            'body' => json_encode([
                'to' => $deviceToken, //Please note: it can be extended by supporting multiple customer's devices
                'data' => [
                    'message' => $notification->message(),
                ]
            ]),
        ]);

        $responseContent = json_decode($response->getContent(), flags: JSON_THROW_ON_ERROR);

        assert(true === $responseContent->success, 'Response from Push is not successful.');
    }

    private function getDeviceToken(Notification $notification): string
    {
        $customer = $this->customerRepository->getById($notification->customerId());

        $deviceToken = $customer->deviceToken();

        if ($deviceToken) {
            return $deviceToken;

        }

        throw new \Exception('No device token available.');
    }
}
