<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\Customer;
use App\ValueObject\CustomerId;
use App\ValueObject\EmailAddress;
use App\ValueObject\PhoneNumber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'notifier:seed-example-data')]
final class SeedExampleDataCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //@TODO: It can be extracted to a separate service
        $customers = [
            new Customer(
                CustomerId::fromString('811f4658-f419-4234-a634-7fccb367a107'),
                EmailAddress::fromString('customer1@example.com'),
                PhoneNumber::fromString('+4800000001'),
            ),
            new Customer(
                CustomerId::fromString('886c906e-1fce-4e8b-ac32-3f26c91d9af3'),
                EmailAddress::fromString('customer2@example.com'),
                PhoneNumber::fromString('+48515425015'),
            ),
            new Customer(
                CustomerId::fromString('57f1f35e-c9bf-4310-8973-95e214b2dbd1'),
                EmailAddress::fromString('customer3@example.com'),
                PhoneNumber::fromString('+4800000003'),
            ),
        ];
        foreach ($customers as $customer) {
            $this->entityManager->persist($customer);
        }

        $this->entityManager->flush();

        $output->writeln('Example data was added to database!');

        return Command::SUCCESS;
    }
}
