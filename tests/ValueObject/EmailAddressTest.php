<?php
declare(strict_types=1);

namespace App\Tests\ValueObject;

use App\ValueObject\EmailAddress;
use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{
    public function testEmailAddressCanBeInstantiated(): void
    {
        $email = EmailAddress::fromString('email@acme.com');

        self::assertSame('email@acme.com', (string) $email);
    }

    /**
     * @dataProvider invalidEmails
     */
    public function testInvalidEmail(string $email): void
    {
        self::expectExceptionObject(new \InvalidArgumentException('"'.$email.'" seems to be not valid e-mail address.'));

        EmailAddress::fromString($email);
    }

    public function invalidEmails(): array
    {
        return [
            ['inavlid_email'],
            ['inavlid_email@'],
            ['email@domain.'],
            ['+1344545'],
            [''],
        ];
    }
}
