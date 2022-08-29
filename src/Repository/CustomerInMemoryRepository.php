<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use App\Exception\CustomerNotFoundException;
use App\ValueObject\CustomerId;

final class CustomerInMemoryRepository implements CustomerRepository
{
    /**
     * @param Customer[] $customers
     */
    public function __construct(
        private readonly array $customers
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getById(CustomerId $id): Customer
    {
        foreach ($this->customers as $customer) {
            if ($customer->id()->equals($id)) {
                return $customer;
            }
        }

        throw new CustomerNotFoundException($id);
    }
}
