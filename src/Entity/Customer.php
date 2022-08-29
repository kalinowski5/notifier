<?php
declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\CustomerId;
use App\ValueObject\EmailAddress;
use App\ValueObject\PhoneNumber;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Customer
{
    #[ORM\Id]
    #[ORM\Column(type: "string", unique: true)]
    private string $id;

    #[ORM\Column(type: "string", unique: true)]
    private string $email;

    #[ORM\Column(type: "string")]
    private string $phoneNumber;

    public function __construct(CustomerId $id, EmailAddress $email, PhoneNumber $phoneNumber)
    {
        $this->id = (string) $id;
        $this->email = (string) $email;
        $this->phoneNumber = (string) $phoneNumber;
    }

    public function id(): CustomerId
    {
        return CustomerId::fromString($this->id);
    }

    public function email(): EmailAddress
    {
        return EmailAddress::fromString($this->email);
    }

    public function phoneNumber(): PhoneNumber
    {
        return PhoneNumber::fromString($this->phoneNumber);
    }
}
