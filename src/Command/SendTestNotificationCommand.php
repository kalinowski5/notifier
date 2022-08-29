<?php
declare(strict_types=1);


namespace App\Command;

use App\Entity\Customer;
use App\Model\Notification;
use App\Service\NotificationChannel;
use App\Service\Notifier;
use App\ValueObject\CustomerId;
use App\ValueObject\EmailAddress;
use App\ValueObject\PhoneNumber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'notifier:send-test-notification')]
final class SendTestNotificationCommand extends Command
{

    public function __construct(
//        private readonly EntityManagerInterface $entityManager,
        private readonly Notifier $notifier,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exampleCustomer = new Customer(
            CustomerId::fromString('2b9a44af-373f-4277-a9eb-e3c33efbe8d6'),
            EmailAddress::fromString('test@example.com'),
            PhoneNumber::fromString('+48515452015'),
        );

//        $this->entityManager->persist($exampleCustomer);
//        $this->entityManager->flush();

        $notification = new Notification(
            CustomerId::fromString('2b9a44af-373f-4277-a9eb-e3c33efbe8d6'),
            'Example subject',
            'Example message',
        );

        $this->notifier->sendNotification($notification);

        $output->writeln('Test notification was sent!');

        return Command::SUCCESS;
    }
}
