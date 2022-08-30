<?php
declare(strict_types=1);


namespace App\ValueObject;

final class EmailAddress
{
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf('"%s" seems to be not valid e-mail address.', $value));
        }

        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function __toString(): string
    {
       return $this->value;
    }
}
