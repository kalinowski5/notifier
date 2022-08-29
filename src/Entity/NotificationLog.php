<?php
declare(strict_types=1);

namespace App\Entity;

use App\Enum\NotificationAttemptStatus;
use App\ValueObject\CustomerId;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
final class NotificationLog
{
    #[ORM\Id]
    #[ORM\Column(type: "string", unique: true)]
    private string $id;

    #[ORM\Column(type: "string")]
    private string $customerId;

    #[ORM\Column(type: "string")]
    private string $notificationChannel;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $sentAt;

    #[ORM\Column(type: "string")]
    private string $status;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $failureReason;

    private function __construct(
        Uuid $id,
        CustomerId $customerId,
        string $notificationChannel,
        \DateTimeImmutable $sentAt,
        NotificationAttemptStatus $status,
        ?string $failureReason,
    ) {
        $this->id = (string) $id;
        $this->customerId = (string) $customerId;
        $this->notificationChannel = $notificationChannel;
        $this->sentAt = $sentAt;
        $this->status = $status->value;
        $this->failureReason = $failureReason;
    }

    public static function successful(
        Uuid $id,
        CustomerId $customerId,
        string $notificationChannel,
        \DateTimeImmutable $sentAt,
    ): self
    {
        return new self($id, $customerId, $notificationChannel, $sentAt, NotificationAttemptStatus::SUCCESSFUL, null);
    }

    public static function failed(
        Uuid $id,
        CustomerId $customerId,
        string $notificationChannel,
        \DateTimeImmutable $sentAt,
        string $failureReason,
    ): self
    {
        return new self($id, $customerId, $notificationChannel, $sentAt, NotificationAttemptStatus::FAILED, $failureReason);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function customerId(): CustomerId
    {
        return CustomerId::fromString($this->customerId);
    }

    public function notificationChannel(): string
    {
        return $this->notificationChannel;
    }

    public function sentAt(): \DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function status(): NotificationAttemptStatus
    {
        return NotificationAttemptStatus::from($this->status);
    }

    public function failureReason(): ?string
    {
        return $this->failureReason;
    }
}
