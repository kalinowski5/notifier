<?php
declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Customer;
use App\ValueObject\CustomerId;
use App\ValueObject\EmailAddress;
use App\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testCustomerCanBeInstantiated(): void
    {
        $customer = new Customer(
            CustomerId::fromString('8b01de17-5d1f-4224-98f4-ad88f9eed6fc'),
            EmailAddress::fromString('email@domain.com'),
            PhoneNumber::fromString('+44 167 903 99'),
        );

        self::assertEquals(CustomerId::fromString('8b01de17-5d1f-4224-98f4-ad88f9eed6fc'), $customer->id());
        self::assertEquals(EmailAddress::fromString('email@domain.com'), $customer->email());
        self::assertEquals(PhoneNumber::fromString('+44 167 903 99'), $customer->phoneNumber());
    }
}
