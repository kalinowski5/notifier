<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use App\Exception\CustomerNotFoundException;
use App\ValueObject\CustomerId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<Customer>
 */
final class CustomerDoctrineRepository extends ServiceEntityRepository implements CustomerRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * @inheritDoc
     */
    public function getById(CustomerId $id): Customer
    {
        $customer = $this->find((string) $id);

        if ($customer) {
            return $customer;
        }

        throw new CustomerNotFoundException($id);
    }
}
