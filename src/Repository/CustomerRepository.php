<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use App\Exception\CustomerNotFoundException;
use App\ValueObject\CustomerId;

interface CustomerRepository
{
    /**
     * @throws CustomerNotFoundException
     */
    public function getById(CustomerId $id): Customer;
}
