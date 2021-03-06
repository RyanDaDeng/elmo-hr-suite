<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:16 PM
 */

namespace App\ELMOHRSuite\Apps\AwardApp\Commands\Collection;

use App\ELMOHRSuite\Apps\AwardApp\Commands\AbstractAwardsCommandBase;
use App\ELMOHRSuite\Apps\AwardApp\Services\AwardService;
use App\ELMOHRSuite\Core\Commands\AbstractSlackCommand;
use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;
use Carbon\Carbon;

class CookiesYouSentCommand extends AbstractAwardsCommandBase
{

    /**
     * @var string $name
     */
    protected $commandName = 'sent';

    /**
     * @var string $description
     */
    protected $description = 'Check how many cookies your have sent.';

    /**
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {

        $service        = new AwardService();
        $receivedAwards = $service->showMySent($this->slackUser->slack_user_id);

        if (!$receivedAwards) {
            return "You don't have any awards received";
        }

        $blocks = [
            [
                'type' => 'section',
                'text' =>
                    [
                        'type' => 'mrkdwn',
                        'text' => '*You have sent awards to: *'
                    ]
            ],
            [
                'type' => 'divider'
            ],
            [
                'type' => 'section',
                'text' =>
                    [
                        'type' => 'mrkdwn',
                        'text' => SlackMessageFormatter::withParagraphs(

                            ':trophy:' . SlackMessageFormatter::inlineBoldText($service->getTotalEmployeeOfTheMonth($this->slackUser->slack_user_id)),
                            '',
                            ':first_place_medal:' . SlackMessageFormatter::inlineBoldText($service->getTotalHighPerformance($this->slackUser->slack_user_id)),
                            '',
                            ':cookie:' . SlackMessageFormatter::inlineBoldText($service->getTotalTeamRecognition($this->slackUser->slack_user_id))
                        )
                    ]
            ],
            [
                'type' => 'divider'
            ]
        ];


        foreach ($receivedAwards as $receivedAward) {
            $blocks[] =
                [
                    'type'   => 'section',
                    'fields' =>
                        [
                            [
                                'type' => 'mrkdwn',
                                'text' => SlackMessageFormatter::withParagraphs(
                                    '*Type*',
                                    $receivedAward->emoji
                                )
                            ],
                            [
                                'type' => 'mrkdwn',
                                'text' => SlackMessageFormatter::withParagraphs(
                                    '*Receiver*',
                                    $receivedAward->receiver_slack_format
                                )
                            ],
                            [
                                'type' => 'mrkdwn',
                                'text' => SlackMessageFormatter::withParagraphs(
                                    '*Quantity*',
                                    SlackMessageFormatter::inlineBoldText($receivedAward->quantity)
                                )
                            ],
                            [
                                'type' => 'mrkdwn',
                                'text' => SlackMessageFormatter::withParagraphs(
                                    '*When*',
                                    Carbon::parse($receivedAward->sent_at)->format('H:m a, d/m/y')
                                )
                            ],
                            [
                                'type' => 'mrkdwn',
                                'text' => SlackMessageFormatter::withParagraphs(
                                    '*Reason*',
                                    $receivedAward->reason
                                )
                            ],

                        ]
                ];

            $blocks[] = [
                'type' => 'divider'
            ];
        }

        return [
            'text'   => 'The awards you received:',
            'blocks' => $blocks
        ];
    }
}