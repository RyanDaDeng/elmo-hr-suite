<?php


namespace App\ELMOHRSuite\AwardApp\Events;


use App\ELMOHRSuite\AwardApp\Events\Collections\BotJoinedChannel;
use App\ELMOHRSuite\AwardApp\Events\Collections\CookieInMessage;
use App\ELMOHRSuite\Core\Events\AbstractSlackEventManager;

class AwardsEventManager extends AbstractSlackEventManager
{

    /**
     * @var $eventCallbacks
     */
    protected $events = [
        'event_callback' => [
            'message.bot_message'   => false,
            'message'               => [
                CookieInMessage::class
            ],
            'member_joined_channel' => [
                BotJoinedChannel::class
            ]
        ]
    ];
}