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
use Carbon\Carbon;
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

        $payloadData = [
            'data' => $data
        ];


        $blocks =
            array(
                0 =>
                    array(
                        'type' => 'section',
                        'text' =>
                            array(
                                'type' => 'mrkdwn',
                                'text' => '*You have a new request*',
                            ),
                    ),
                1 =>
                    array(
                        'type'   => 'section',
                        'fields' =>
                            array(
                                0 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => '*:runner: *Applicant*',
                                    ),
                                1 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => ':bell: *Type*',
                                    ),
                                2 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => $user,
                                    ),
                                3 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => $this->leaveMapping[$data['leave_type']],
                                    ),
                            ),
                    ),
                2 =>
                    array(
                        'type' => 'divider',
                    ),
                3 =>
                    array(
                        'type'   => 'section',
                        'fields' =>
                            array(
                                0 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => ':clock10: *Start Date*',
                                    ),
                                1 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => ':clock1030: *End Date*',
                                    ),
                                2 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => Carbon::parse($data['start_date'])->format('Y-m-d'),
                                    ),
                                3 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => Carbon::parse($data['start_date'])->addDays($data['days'])->format('Y-m-d'),
                                    ),
                            ),
                    ),
                4 =>
                    array(
                        'type'   => 'section',
                        'fields' =>
                            array(
                                0 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => ':calendar: *Submitted at*',
                                    ),
                                1 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => ':mag: *Approver*',
                                    ),
                                2 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => Carbon::now()->format('Y-m-d'),
                                    ),
                                3 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => $user,
                                    ),
                            ),
                    ),
                5 =>
                    array(
                        'type' => 'section',
                        'text' =>
                            array(
                                'type' => 'mrkdwn',
                                'text' => SlackMessageFormatter::withParagraphs(
                                    ':speech_balloon: *Reason* ',
                                    $data['reason']
                                ),
                            ),
                    ),
                6 =>
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
                                        'value'     => json_encode($data), //todo,
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
                                        'value'     => json_encode($data),
                                        'action_id' => Store::LEAVE_DECLINED_ACTION
                                    ),
                            ),
                    ),
                7 =>
                    array(
                        'type' => 'divider',
                    )
            );



        $slackBotApi->postMessage(
            [
                'as_user' => true,
                'channel' => $channelId,
                'text'    => '@here ' . $user .
                    ' from ' . $this->teamMapping[$data['team']] . ' team has requested a ' .
                    $this->leaveMapping[$data['leave_type']] . ' on ' . $data['start_date'] .
                    ' for ' . $data['days'] . ' days.',
                'blocks'  => $blocks
            ]
        );
    }

}
