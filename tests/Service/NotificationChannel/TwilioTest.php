<?php
declare(strict_types=1);

namespace App\Tests\Service\NotificationChannel;

use App\Entity\Customer;
use App\Exception\CustomerNotFoundException;
use App\Model\Notification;
use App\Repository\CustomerInMemoryRepository;
use App\Service\NotificationChannel\Twilio;
use App\ValueObject\CustomerId;
use App\ValueObject\EmailAddress;
use App\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;
use Twilio\Rest\Api\V2010\Account\MessageList;
use Twilio\Rest\Client;

class TwilioTest extends TestCase
{
    public function testMessageIsBeingSent(): void
    {
        $twilioClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $messageListMock = $this->getMockBuilder(MessageList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $messageListMock->expects($this->once())
            ->method('create')
            ->with('+48123456789');

        $twilioClient->messages = $messageListMock;

        $systemUnderTest = new Twilio($this->getCustomerRepository(), $twilioClient, '+28349940340');

        $notification = new Notification(
            CustomerId::fromString('d08f8c6f-7c7b-41f9-acba-0c5da6a7a578'),
            'Example subject',
            'Example message',
        );
        $systemUnderTest->sendNotification($notification);
    }

    public function testCustomerNotFound(): void
    {
        self::expectExceptionObject(
            new CustomerNotFoundException(CustomerId::fromString('c7401a1c-87e6-4345-abf0-56df4a1b66bd'))
        );

        $twilioClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $systemUnderTest = new Twilio($this->getCustomerRepository(), $twilioClient, '+28349940340');

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
}
