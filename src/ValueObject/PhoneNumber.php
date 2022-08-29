<?php
declare(strict_types=1);


namespace App\ValueObject;

final class PhoneNumber
{
    private const MIN_LENGTH = 6;

    private string $value;

    public function __construct(string $value)
    {
        if (strlen(trim($value)) < self::MIN_LENGTH) {
            throw new \InvalidArgumentException(sprintf('Phone number must have at least %s characters.', self::MIN_LENGTH));
        }

        //@TODO: More sophisticated rules can be added

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
