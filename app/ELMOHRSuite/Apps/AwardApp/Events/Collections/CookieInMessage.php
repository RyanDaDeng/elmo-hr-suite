<?php


namespace App\ELMOHRSuite\Apps\AwardApp\Events\Collections;


use App\ELMOHRSuite\Core\Api\SlackBotApi;
use App\ELMOHRSuite\Core\Events\AbstractSlackEvent;
use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;
use App\ELMOHRSuite\Core\Helpers\SlackMessageParser;
use Illuminate\Support\Facades\Log;

class CookieInMessage extends AbstractSlackEvent
{

    public $cookieCount = 0;
    public $mentionedUsers = [];

    public function validate(): bool
    {
        $event = $this->payload['event'];

        $this->cookieCount = SlackMessageParser::countEmoji(':cookie:', $event['text']);

        $this->mentionedUsers = SlackMessageParser::getMentionedUsers($event['text'], [$event['user']]);

        if ($this->cookieCount && $this->mentionedUsers && $event['channel_type'] === 'channel' &&
            $event['user'] != 'USLACKBOT' &&
            $event['user'] != config('slack.awards.bot_user_id') &&
            $event['channel'] != 'CRSCFAWVB' //todo hard-coded

        ) {
            return true;
        }
        return false;
    }

    public function handle(): void
    {
        $event       = $this->payload['event'];
        $slackBotApi = SlackBotApi::instance(config('slack.awards.bot_access_token'));
        $slackBotApi->postMessage(
            [
                'channel' => $this->payload['event']['channel'],
                'text'    => SlackMessageFormatter::mentionUserIds($this->mentionedUsers) .
                    ' received ' . SlackMessageFormatter::inlineBoldText($this->cookieCount) .
                    ' cookie(s) from ' . SlackMessageFormatter::mentionUserId($event['user'])
            ]
        );


        $slackBotApi->postMessage(
            [
                'channel' => 'CRSCFAWVB',
                'text'    =>

                    SlackMessageFormatter::paragraphs([
                            SlackMessageFormatter::mentionUserIds($this->mentionedUsers) .
                            ' received ' . SlackMessageFormatter::inlineBoldText($this->cookieCount) .
                            ' cookie(s) from ' . SlackMessageFormatter::mentionUserId($event['user']),
                            SlackMessageFormatter::quote(SlackMessageFormatter::italic(SlackMessageFormatter::mentionUserId($event['user'])) . ': ' . $event['text'])
                        ]
                    )
            ]
        );
    }
}