<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Enum\DeliveryPolicy;
use App\Model\Notification;
use App\Service\NotificationChannel\AlwaysBroken;
use App\Service\NotificationChannel\DevNull;
use App\Service\NotificationChannel\Spyable;
use App\Service\NotificationLogger;
use App\Service\Notifier;
use App\ValueObject\CustomerId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NotifierTest extends TestCase
{
    private NotificationLogger&MockObject $notificationLogger;

    protected function setUp(): void
    {
        $this->notificationLogger = $this->getMockBuilder(NotificationLogger::class)
            ->disableOriginalConstructor()
            ->getMock();

        parent::setUp();
    }

    public function testNotificationsCanBeSentViaAllAvailableChannels(): void
    {
        $deliveryPolicy = DeliveryPolicy::ALL_CHANNELS;

        $notificationChannel = new Spyable();

        $systemUnderTest = new Notifier([
            $notificationChannel,
            $notificationChannel,
            $notificationChannel,
        ], $deliveryPolicy->value, $this->notificationLogger);

        $notification = self::exampleNotification();

        $systemUnderTest->sendNotification($notification);

        self::assertEquals([
            $notification,
            $notification,
            $notification,
        ], $notificationChannel->notificationsSent());
    }

    public function testNonWorkingChannelsDontBreakSystem(): void
    {
        $deliveryPolicy = DeliveryPolicy::ALL_CHANNELS;

        $spyableNotificationChannel = new Spyable();
        $brokenNotificationChannel = new AlwaysBroken();

        $systemUnderTest = new Notifier([
            $spyableNotificationChannel,
            $brokenNotificationChannel,
            $spyableNotificationChannel,
        ], $deliveryPolicy->value, $this->notificationLogger);

        $notification = self::exampleNotification();

        $systemUnderTest->sendNotification($notification);

        self::assertEquals([
            $notification,
            $notification,
        ], $spyableNotificationChannel->notificationsSent());
    }

    public function testNotificationIsBeingSentOnlyByOneChannel(): void
    {
        $deliveryPolicy = DeliveryPolicy::FIRST_WORKING_CHANNEL;

        $notificationChannel = new Spyable();
        $anotherNotificationChannel = new Spyable();

        $systemUnderTest = new Notifier([
            $notificationChannel,
            $anotherNotificationChannel,
            $notificationChannel,
        ], $deliveryPolicy->value, $this->notificationLogger);

        $notification = self::exampleNotification();

        $systemUnderTest->sendNotification($notification);

        self::assertEquals([
            $notification,
        ], $notificationChannel->notificationsSent());

        self::assertEmpty($anotherNotificationChannel->notificationsSent());
    }

    public function testNotificationIsBeingSentByFirstWorkingChannel(): void
    {
        $deliveryPolicy = DeliveryPolicy::FIRST_WORKING_CHANNEL;

        $notificationChannel = new Spyable();
        $brokenNotificationChannel = new AlwaysBroken();

        $systemUnderTest = new Notifier([
            $brokenNotificationChannel,
            $notificationChannel,
            $notificationChannel,
        ], $deliveryPolicy->value, $this->notificationLogger);

        $notification = self::exampleNotification();

        $systemUnderTest->sendNotification($notification);

        self::assertEquals([
            $notification,
        ], $notificationChannel->notificationsSent());
    }

    public function testNotificationsAreLogged(): void
    {
        $this->notificationLogger
            ->expects($this->exactly(2))
            ->method('logSuccess')
            ->willReturnCallback(function (CustomerId $customerId, string $channel) {
                self::assertEquals(CustomerId::fromString('d33fce8b-a846-483a-8c4f-49bdc93b8eb0'), $customerId);
                self::assertEquals(DevNull::class, $channel);
            });
        $this->notificationLogger
            ->expects($this->exactly(1))
            ->method('logFailure')
            ->willReturnCallback(function (CustomerId $customerId, string $channel) {
                self::assertEquals(CustomerId::fromString('d33fce8b-a846-483a-8c4f-49bdc93b8eb0'), $customerId);
                self::assertEquals(AlwaysBroken::class, $channel);
            });

        $deliveryPolicy = DeliveryPolicy::ALL_CHANNELS;

        $channel1 = new DevNull();
        $channel2 = new AlwaysBroken();

        $systemUnderTest = new Notifier([
            $channel1,
            $channel1,
            $channel2,
        ], $deliveryPolicy->value, $this->notificationLogger);

        $notification = self::exampleNotification();

        $systemUnderTest->sendNotification($notification);
    }

    private static function exampleNotification(): Notification
    {
        return new Notification(
            CustomerId::fromString('d33fce8b-a846-483a-8c4f-49bdc93b8eb0'),
            'Subject',
            'Lorem ipsum...',
        );
    }
}
