<?php
declare(strict_types=1);

namespace App\Tests\Service\NotificationChannel;

use App\Entity\Customer;
use App\Exception\CustomerNotFoundException;
use App\Model\Notification;
use App\Repository\CustomerInMemoryRepository;
use App\Service\NotificationChannel\AmazonSES;
use App\ValueObject\CustomerId;
use App\ValueObject\EmailAddress;
use App\ValueObject\PhoneNumber;
use Aws\Ses\SesClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AmazonSesTest extends TestCase
{
    public function testMessageIsBeingSent(): void
    {
        $sesClient = $this->getSesClient();

        $sesClient->expects($this->once())
            ->method('sendEmail')
            ->with([
                'Destination' => [
                    'ToAddresses' => ['email@example.com'],
                ],
                'ReplyToAddresses' => ['admin@acme.com'],
                'Source' => 'admin@acme.com',
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => 'UTF-8',
                            'Data' => '<p>Lorem ipsum dot...</p>',
                        ],
                        'Text' => [
                            'Charset' => 'UTF-8',
                            'Data' => 'Lorem ipsum dot...',
                        ],
                    ],
                    'Subject' => [
                        'Charset' => 'UTF-8',
                        'Data' => 'Example subject',
                    ],
                ],
            ]);


        $systemUnderTest = new AmazonSES($this->getCustomerRepository(), $sesClient, 'admin@acme.com');

        $notification = new Notification(
            CustomerId::fromString('d08f8c6f-7c7b-41f9-acba-0c5da6a7a578'),
            'Example subject',
            'Lorem ipsum dot...',
        );
        $systemUnderTest->sendNotification($notification);
    }

    public function testCustomerNotFound(): void
    {
        self::expectExceptionObject(
            new CustomerNotFoundException(CustomerId::fromString('c7401a1c-87e6-4345-abf0-56df4a1b66bd'))
        );

        $systemUnderTest = new AmazonSES($this->getCustomerRepository(), $this->getSesClient(), 'admin@acme.com');

        $notification = new Notification(
            CustomerId::fromString('c7401a1c-87e6-4345-abf0-56df4a1b66bd'),
            'Example subject',
            'Example message',
        );
        $systemUnderTest->sendNotification($notification);
    }

    private function getCustomerRepository(): CustomerInMemoryRepository
    {
        $customer1 = new Customer(
            CustomerId::fromString('f8e052ad-31ce-439a-b383-3ad9d77a8abd'),
            EmailAddress::fromString('address@acme.com'),
            PhoneNumber::fromString('+22049948493'),
        );

        $customer2 = new Customer(
            CustomerId::fromString('d08f8c6f-7c7b-41f9-acba-0c5da6a7a578'),
            EmailAddress::fromString('email@example.com'),
            PhoneNumber::fromString('+48123456789'),
        );

        return new CustomerInMemoryRepository([
            $customer1,
            $customer2,
        ]);
    }

    private function getSesClient(): SesClient&MockObject
    {
        return $this->getMockBuilder(SesClient::class)
            ->disableOriginalConstructor()
            ->addMethods(['sendEmail'])
            ->getMock();
    }
}
