<?php


namespace App\ELMOHRSuite\Apps\AwardApp\Events\Collections;

use App\ELMOHRSuite\Core\Api\SlackBotApi;
use App\ELMOHRSuite\Core\Events\AbstractSlackEvent;

class BotJoinedChannel extends AbstractSlackEvent
{

    public function validate(): bool
    {
        $event = $this->payload['event'];
        if ($event['user'] === config('slack.awards.bot_user_id') && $event['channel_type'] === 'C') {
            return true;
        }
        return false;
    }

    public function handle(): void
    {
        SlackBotApi::instance(config('slack.awards.bot_access_token'))
            ->postMessage(
                [
                    'channel' => $this->payload['event']['channel'],
                    'text'    => 'Thank you for installing me ! I am ELMO Awards Bot!'
                ]
            );
    }
}