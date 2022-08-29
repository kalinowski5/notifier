<?php
declare(strict_types=1);


namespace App\ValueObject;


use Symfony\Component\Uid\Uuid;

final class CustomerId
{
    private Uuid $value;

    private function __construct(Uuid $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $string): self
    {
        return new self(Uuid::fromString($string));
    }

    public function equals(self $other): bool
    {
        return $this->value->equals($other->value);
    }

    public function __toString(): string
    {
       return (string) $this->value;
    }
}
