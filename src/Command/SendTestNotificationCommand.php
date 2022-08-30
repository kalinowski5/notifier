<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\Customer;
use App\Model\Notification;
use App\Service\Notifier;
use App\ValueObject\CustomerId;
use App\ValueObject\EmailAddress;
use App\ValueObject\PhoneNumber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(name: 'notifier:send-test-notification')]
final class SendTestNotificationCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Notifier $notifier,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exampleCustomer = $this->seedExampleCustomer();

        $notification = new Notification(
            $exampleCustomer->id(),
            'Example subject',
            'Example message',
        );

        $this->notifier->sendNotification($notification);

        $output->writeln('Test notification was sent via all available channels.');
        $output->writeln('Check `notification_log` table for details.');

        return Command::SUCCESS;
    }

    private function seedExampleCustomer(): Customer
    {
        $customerId = CustomerId::fromUuid(Uuid::v4());

        $exampleCustomer = new Customer(
            $customerId,
            EmailAddress::fromString(substr((string)$customerId, 5).'@example.com'),
            PhoneNumber::fromString('+48515425015'),
        );

        $exampleCustomer->setDeviceToken('0ad39f9c');

        $this->entityManager->persist($exampleCustomer);
        $this->entityManager->flush();

        return $exampleCustomer;
    }
}
