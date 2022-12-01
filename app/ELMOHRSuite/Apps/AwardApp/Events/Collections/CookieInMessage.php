<?php


namespace App\ELMOHRSuite\Apps\AwardApp\Events\Collections;


use App\ELMOHRSuite\Apps\AwardApp\Services\AwardService;
use App\ELMOHRSuite\Core\Api\SlackBotApi;
use App\ELMOHRSuite\Core\Events\AbstractSlackEvent;
use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;
use App\ELMOHRSuite\Core\Helpers\SlackMessageParser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CookieInMessage extends AbstractSlackEvent
{

    public $cookieCount = 0;

    public $mentionedUsers = [];

    public $notificationChannel = 'CRVK9C1RN'; //todo hard-coded

    public function validate(): bool
    {
        Log::info('11');
        $event = $this->payload['event'];

        $this->cookieCount = SlackMessageParser::countEmoji(':cookie:', $event['text']);

        $this->mentionedUsers = SlackMessageParser::getMentionedUsers($event['text'], [$event['user']]);
        if ($this->cookieCount && $this->mentionedUsers && $event['channel_type'] === 'channel' &&
            $event['user'] != 'USLACKBOT' &&
            $event['user'] != config('slack.awards.bot_user_id') &&
            $event['channel'] != $this->notificationChannel

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


        $awardService = new AwardService();
        foreach ($this->mentionedUsers as $receiver) {
            $awardService->sendTeamRecognition($event['user'], $receiver, Carbon::now()->format('Y-m-d H:i:s'),
                $this->cookieCount, $event['text']);
        }


        $slackBotApi->postMessage(
            [
                'channel' => $this->notificationChannel,
                'text'    =>

                    SlackMessageFormatter::paragraphs([
                            SlackMessageFormatter::mentionUserIds($this->mentionedUsers) .
                            ' received ' . SlackMessageFormatter::inlineBoldText($this->cookieCount) .
                            ' cookie(s) from ' . SlackMessageFormatter::mentionUserId($event['user']) . ' in channel ' . SlackMessageFormatter::mentionChannel($event['channel']),
                            SlackMessageFormatter::quote(SlackMessageFormatter::italic($event['text']))
                        ]
                    )
            ]
        );
    }
}