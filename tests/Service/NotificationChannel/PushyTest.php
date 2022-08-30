<?php
declare(strict_types=1);

namespace App\Tests\Service\NotificationChannel;

use App\Entity\Customer;
use App\Exception\CustomerNotFoundException;
use App\Model\Notification;
use App\Repository\CustomerInMemoryRepository;
use App\Service\NotificationChannel\Pushy;
use App\ValueObject\CustomerId;
use App\ValueObject\EmailAddress;
use App\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class PushyTest extends TestCase
{
    public function testPushNotificationIsBeingSent(): void
    {
        $httpClient = $this->getHttpClient();
        $systemUnderTest = new Pushy($this->getCustomerRepository(), $httpClient, 'SECRET_API_KEY');

        $notification = new Notification(
            CustomerId::fromString('f8e052ad-31ce-439a-b383-3ad9d77a8abd'),
            'Example subject',
            'This is Pushy message',
        );
        $systemUnderTest->sendNotification($notification);

        self::assertEquals(1, $httpClient->getRequestsCount());
    }

    public function testCustomerNotFound(): void
    {
        self::expectExceptionObject(
            new CustomerNotFoundException(CustomerId::fromString('c7401a1c-87e6-4345-abf0-56df4a1b66bd'))
        );

        $systemUnderTest = new Pushy($this->getCustomerRepository(), new MockHttpClient(), 'SECRET_API_KEY');

        $notification = new Notification(
            CustomerId::fromString('c7401a1c-87e6-4345-abf0-56df4a1b66bd'),
            'Example subject',
            'Example message',
        );
        $systemUnderTest->sendNotification($notification);
    }

    private function getCustomerRepository(): CustomerInMemoryRepository
    {
        $customer = new Customer(
            CustomerId::fromString('f8e052ad-31ce-439a-b383-3ad9d77a8abd'),
            EmailAddress::fromString('address@acme.com'),
            PhoneNumber::fromString('+22049948493'),
        );

        $customer->setDeviceToken('DEVICE_TOKEN_ABC');

        return new CustomerInMemoryRepository([$customer]);
    }

    private function getHttpClient(): MockHttpClient
    {
        $callback = function (string $method, string $url, array $options) {

            if ('POST' !== $method) {
                return new MockResponse('Expected POST method.', ['http_code' => 400]);
            }

            if ('https://api.pushy.me/push?api_key=SECRET_API_KEY' !== $url) {
                return new MockResponse('It seems Push url is wrong.', ['http_code' => 400]);
            }

            if (!in_array('Content-type: application/json', $options['headers'], true)) {
                return new MockResponse('Wrong Content-type headers', ['http_code' => 400]);
            }

            return new MockResponse(
                '{
                        "success": true,
                        "id": "5ea9b214b47cad768a35f13a",
                        "info": {
                            "devices": 1
                        }
                    }'
            );
        };

        return new MockHttpClient($callback);
    }
}
