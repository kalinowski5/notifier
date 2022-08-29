<?php
declare(strict_types=1);

namespace App\Model;

use App\ValueObject\CustomerId;

final class Notification
{
    public function __construct(
        private readonly CustomerId $customerId,
        private readonly string $subject,
        private readonly string $message,
    ) {

    }

    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function message(): string
    {
        return $this->message;
    }
}
