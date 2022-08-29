<?php
declare(strict_types=1);

namespace App\Exception;

use App\ValueObject\CustomerId;

final class CustomerNotFoundException extends \Exception
{
    public function __construct(CustomerId $customerId)
    {
        parent::__construct(sprintf('Customer "%s" not found.', $customerId));
    }
}
