<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\NotificationLog;
use App\ValueObject\CustomerId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class NotificationLogger
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function logSuccess(CustomerId $customerId, string $channel): void
    {
        $log = NotificationLog::successful(
            Uuid::v4(),
            $customerId,
            $channel,
            new \DateTimeImmutable(), //@TODO: inject clock
        );

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function logFailure(CustomerId $customerId, string $channel, string $reason): void
    {
        $log = NotificationLog::failed(
            Uuid::v4(),
            $customerId,
            $channel,
            new \DateTimeImmutable(), //@TODO: inject clock
            $reason,
        );

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
