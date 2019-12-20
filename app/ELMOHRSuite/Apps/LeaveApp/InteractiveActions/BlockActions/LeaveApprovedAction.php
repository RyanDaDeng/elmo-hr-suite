<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/7/31
 * Time: 7:34 PM
 */

namespace App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\BlockActions;

use App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\Store;
use App\ELMOHRSuite\Core\Api\SlackBotApi;
use App\ELMOHRSuite\Core\Api\SlackClientApi;
use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;
use App\ELMOHRSuite\Core\InteractiveManager\AbstractInteractive;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;

class LeaveApprovedAction extends AbstractInteractive
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
    /**
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle()
    {
        $api = SlackClientApi::instance(
            config('slack.leave.client_access_token')
        );

        $slackBotApi = SlackBotApi::instance(config('slack.leave.bot_access_token'));

        // todo
        $data = json_decode($this->payload['actions'][0]['value'], true);
        $data['approvalStatus'] = 4;
        $jsonData  = base64_encode(json_encode($data));
        $this->sendPostMessage($data['manager'], $slackBotApi, $data, $this->payload["response_url"]);
        $client = new Client(['verify' => false ]);
        $res = $client->get('https://dev.local.elmodev.com/api/v0/form/entity-choice?data='. $jsonData);

        //var_dump('https://ralphtest.dev.elmodev.com/api/v0/form/entity-choice?data='. $jsonData);die();
        return Response::json(['1234'], 200);
    }

    /**
     * @param SlackBotApi $slackBotApi
     * @param array $data
     */
    public function sendPostMessage($channelId, SlackBotApi $slackBotApi, array $data, $url): void
    {
        $user    = SlackMessageFormatter::mentionUserId($data['user']);
        $manager = SlackMessageFormatter::mentionUserId($data['manager']);

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
                                        'text' => ':clock1030: *Duration*',
                                    ),
                                2 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => $data['start_date'],
                                    ),
                                3 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => $data['days']. ' days',
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
                                        'text' => Carbon::now()->format('d/m/Y'),
                                    ),
                                3 =>
                                    array(
                                        'type' => 'mrkdwn',
                                        'text' => $manager,
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
                        'type' => 'section',
                        'text' =>
                            array(
                                'type' => 'mrkdwn',
                                'text' => ':white_check_mark: *Approved* ',
                            ),
                    ),
                7 =>
                    array(
                        'type' => 'divider',
                    )
            );
        $slackBotApi->updateMessageByUrl(
            [
                'as_user' => true,
                'channel' => $channelId,
                'text'    => 'updated',
                'blocks'  => $blocks
            ], $url
        );
    }
}
