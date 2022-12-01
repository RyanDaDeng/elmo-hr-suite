<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:16 PM
 */

namespace App\ELMOHRSuite\Apps\AwardApp\Commands\Collection;

use App\ELMOHRSuite\Apps\AwardApp\Commands\AbstractAwardsCommandBase;
use App\ELMOHRSuite\Core\Api\SlackClientApi;
use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;


class CookiesBalanceCommand extends AbstractAwardsCommandBase
{

    /**
     * @var string $name
     */
    protected $commandName = 'balance';

    /**
     * @var string $description
     */
    protected $description = 'Check your current cookies balance';

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
        $message = [
            'text'   => 'Hey, you have the following balance:',
            'blocks' => [
                [
                    'type' => 'section',
                    'text' =>
                        [
                            'type' => 'mrkdwn',
                            'text' => '*Your remaining balance: *'

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
                            'text' =>
                                ':first_place_medal: *High Performance Balance* : ' . SlackMessageFormatter::inlineBoldText($this->slackUser->high_performance_balance) . ' vote(s)'

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
                            'text' =>
                                ':trophy: *Employee of The Month Balance* : ' . SlackMessageFormatter::inlineBoldText($this->slackUser->employee_of_the_month_balance) . ' vote(s)'

                        ]
                ],
                [
                    'type' => 'divider'
                ]
            ]
        ];


        return $message;
    }
}