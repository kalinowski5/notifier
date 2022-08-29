<?php
declare(strict_types=1);


namespace App\Enum;

enum DeliveryPolicy: string
{
    /**
     * Sends notifications via all available channels
     */
    case ALL_CHANNELS = 'all_channels';

    /**
     * Sends notifications only via first working channel
     *
     * For example:
     * There are 3 channels: SMS (temporarily broken), Email (operating normally), Push notification (operating normally)
     * Since SMS provider is offline, only Email notification will be sent
     */
    case FIRST_WORKING_CHANNEL = 'first_working_channel';
}
