<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 8:03 PM
 */

namespace App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\ViewActions;


use App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\Store;
use App\ELMOHRSuite\Apps\LeaveApp\Views\LeaveFormView;
use App\ELMOHRSuite\Core\Api\SlackBotApi;
use App\ELMOHRSuite\Core\Api\SlackClientApi;
use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;
use App\ELMOHRSuite\Core\InteractiveManager\AbstractInteractive;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LeaveFormSubmission extends AbstractInteractive
{
    protected $leaveMapping = [
        'AL' => 'Annual Leave',
        'SL' => 'Sick Leave',
        'HL' => 'Work from home Leave',
    ];

    protected $teamMapping = [
        'ER'  => 'Hire',
        'RTA' => 'RTA',
        'CRT' => 'CRT',
    ];

    protected $channelMap = [
        'ER'  => 'CRWDDRK8W',
        'RTA' => 'CRWDF0Z7G',
        'CRT' => 'CRW36CCT1',
    ];

    //URRTWAZQX


    public function validate()
    {
        return true;
    }

    public function handle()
    {

        $api = SlackClientApi::instance(
            config('slack.leave.client_access_token')
        );

        $dialog = new LeaveFormView();
        $res    = $dialog->validate($this->payload);
        if ($res->isFailed()) {
            return JsonResponse::create([
                'response_action' => 'errors',
                'errors'          => $res->getErrors()
            ], 200);
        }

        Log::info($res->getData());
        $data = $res->getData();

        $slackBotApi = SlackBotApi::instance(config('slack.leave.bot_access_token'));
        $this->sendPostMessage('CRU348L2Z', $slackBotApi, $data);
        $this->sendPostMessage($this->channelMap[$data['team']], $slackBotApi, $data);
        $this->sendPostBlockMessage($data['manager'], $slackBotApi, $data);

        return JsonResponse::create(
            ["response_action" => "clear"],
            200);
    }

    /**
     * @param SlackBotApi $slackBotApi
     * @param array $data
     */
    public function sendPostMessage($channelId, SlackBotApi $slackBotApi, array $data): void
    {
        $slackBotApi->postMessage(
            [
                'as_user' => true,
                'channel' => $channelId,
                'text'    => '@here ' . SlackMessageFormatter::mentionUserId($this->payload['user']['id']) .
                    ' from ' . $this->teamMapping[$data['team']] . ' team has requested a ' .
                    $this->leaveMapping[$data['leave_type']] . ' on ' . $data['start_date'] .
                    ' for ' . $data['days'] . ' days.'
            ]
        );
    }


    /**
     * @param SlackBotApi $slackBotApi
     * @param array $data
     */
    public function sendPostBlockMessage($channelId, SlackBotApi $slackBotApi, array $data): void
    {
        $manager = SlackMessageFormatter::mentionUserId($data['manager']);
        $user    = SlackMessageFormatter::mentionUserId($this->payload['user']['id']);

        $endDate = date('d/m/Y', strtotime("+ {$data['days']} day", strtotime($data['start_date'])));

        $slackBotApi->postMessage(
            [
                'as_user' => true,
                'channel' => $channelId,
                'text'    => '@here ' . $user .
                    ' from ' . $this->teamMapping[$data['team']] . ' team has requested a ' .
                    $this->leaveMapping[$data['leave_type']] . ' on ' . $data['start_date'] .
                    ' for ' . $data['days'] . ' days.',
                'blocks'  => array(
                    0 =>
                        array(
                            'type' => 'section',
                            'text' =>
                                array(
                                    'type' => 'mrkdwn',
                                    'text' => 'New leave request for ' . $user,
                                ),
                        ),
                    1 =>
                        array(
                            'type' => 'divider',
                        ),
                    2 =>
                        array(
                            'type' => 'section',
                            'text' =>
                                array(
                                    'type' => 'mrkdwn',
                                    'text' => "Dear {$manager},

A new leave request for {$user} requires your approval.

Details:
Leave Type: {$this->leaveMapping[$data['leave_type']]}
Duration: {$data['days']} days from {$data['start_date']}

Comments: {$data['reason']}.


Kind Regards, ELMO HR Team",
                                ),
                        ),
                    3 =>
                        array(
                            'type'     => 'actions',
                            'elements' =>
                                array(
                                    0 =>
                                        array(
                                            'type'      => 'button',
                                            'text'      =>
                                                array(
                                                    'type'  => 'plain_text',
                                                    'text'  => 'Approve',
                                                    'emoji' => true,
                                                ),
                                            'style'     => 'primary',
                                            'value'     => 'click_me_123', //todo,
                                            'action_id' => Store::LEAVE_APPROVED_ACTION
                                        ),
                                    1 =>
                                        array(
                                            'type'      => 'button',
                                            'text'      =>
                                                array(
                                                    'type'  => 'plain_text',
                                                    'text'  => 'Reject',
                                                    'emoji' => true,
                                                ),
                                            'style'     => 'danger',
                                            'value'     => 'click_me_123',
                                            'action_id' => Store::LEAVE_DECLINED_ACTION
                                        ),
                                ),
                        ),
                )
            ]
        );
    }

}
