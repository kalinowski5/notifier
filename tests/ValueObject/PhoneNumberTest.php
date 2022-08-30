<?php
declare(strict_types=1);

namespace App\Tests\ValueObject;

use App\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    public function testPhoneNumberCanBeInstantiated(): void
    {
        $phoneNumber = PhoneNumber::fromString('+1904930303');

        self::assertSame('+1904930303', (string) $phoneNumber);
    }

    public function testInvalidPhoneNumber(): void
    {
        self::expectExceptionObject(new \InvalidArgumentException('Phone number must have at least 6 characters.'));

        PhoneNumber::fromString('+123');
    }
}
