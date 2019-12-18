<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 8:03 PM
 */

namespace App\ELMOHRSuite\Apps\AwardApp\InteractiveActions\ViewActions;

use App\ELMOHRSuite\Apps\AwardApp\Models\Award;
use App\ELMOHRSuite\Apps\AwardApp\Models\SlackUser;
use App\ELMOHRSuite\Apps\AwardApp\Services\AwardService;
use App\ELMOHRSuite\Apps\AwardApp\Validations\QuantityValidationRule;
use App\ELMOHRSuite\Apps\AwardApp\Views\AwardFormView;
use App\ELMOHRSuite\Core\Api\SlackBotApi;
use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;
use App\ELMOHRSuite\Core\InteractiveManager\AbstractInteractive;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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
        $payload             = $this->payload;

        /**
         * @var SlackUser $slackUser
         */
        $slackUser = SlackUser::query()->where('slack_user_id', $payload['user']['id'])->first();

        $formData = SlackMessageFormatter::collectData($this->payload);
        switch ($formData['category']) {
            case Award::EMPLOYEE_OF_THE_MONTH:

                $limit = $slackUser->employee_of_the_month_balance;

                break;
            case Award::HIGH_PERFORMANCE:

                $limit = $slackUser->high_performance_balance;
                break;
            default:
                $limit = null;
        }

        $view = new AwardFormView();
        $res  = $view->validate($this->payload, [
            'quantity' => [
                'required',
                'min:1',
                'max:5',
                'integer',
                new QuantityValidationRule($limit)
            ]
        ]);

        if ($res->isFailed()) {
            return JsonResponse::create([
                'response_action' => 'errors',
                'errors'          => $res->getErrors()
            ], 200);
        }


        $awardService = new AwardService();
        $awardService->send(
            $formData['category'],
            $payload['user']['id'],
            $formData['user'],
            $formData['quantity'],
            $formData['reason']
        );

        $slackBotApi      = SlackBotApi::instance(config('slack.awards.bot_access_token'));
        $notificationText =
            SlackMessageFormatter::withParagraphs(

                SlackMessageFormatter::mentionUserId($formData['user']) . ' got ' . SlackMessageFormatter::inlineBoldText($formData['quantity']) .
                Award::getEmojiByCategory($formData['category']) . ' from ' .
                SlackMessageFormatter::mentionUserId($payload['user']['username']),
                SlackMessageFormatter::quote(SlackMessageFormatter::italic($formData['reason']))
            );
        $res              = $slackBotApi->postMessage(
            [
                'channel' => $notificationChannel,
                'text'    => $notificationText,
            ]
        );

        return new JsonResponse(
            [
                'response_action' => 'clear',
                // 'text' => 'message received'
            ]
        );
    }
}