<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 8:03 PM
 */

namespace App\ELMOHRSuite\Apps\AwardApp\InteractiveActions\ViewActions;

use App\ELMOHRSuite\Core\Api\SlackBotApi;
use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;
use App\ELMOHRSuite\Core\InteractiveManager\AbstractInteractive;
use Symfony\Component\HttpFoundation\JsonResponse;

class AwardFormSubmission extends AbstractInteractive
{

    public function validate()
    {
        return true;
    }

    public function handle()
    {
        $notificationChannel = 'CRSCFAWVB';
        $payload = $this->payload;

        $formData = SlackMessageFormatter::collectData($payload);

        $slackBotApi = SlackBotApi::instance(config('slack.awards.bot_access_token'));
        $notificationText = SlackMessageFormatter::mentionUserId($formData['user'] ). ' got ' . $formData['quantity'] . ' cookies from ' . $payload['user']['username'];
        $res = $slackBotApi->postMessage(
            [
                'channel' => $notificationChannel,
                'text' => $notificationText
            ]
        );

        return new JsonResponse(
            [
                'response_action' => 'clear',
                // 'text' => 'message received'
            ]
        );

//        $api = SlackClientApi::instance(
//            config('slack.leave.client_access_token')
//        );
//
//        $dialog = new LeaveFormView();
//        $res = $dialog->validate($this->payload);
//        if ($res->isFailed()) {
//            return JsonResponse::create([
//                'response_action' => 'errors',
//                'errors' => $res->getErrors()
//            ], 200);
//        }
//
//        Log::info($res->getData());
//
    }
}