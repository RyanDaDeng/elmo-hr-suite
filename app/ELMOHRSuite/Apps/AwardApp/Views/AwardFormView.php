<?php

namespace App\ELMOHRSuite\Apps\AwardApp\Views;

use App\ELMOHRSuite\Apps\AwardApp\InteractiveActions\Store;
use App\ELMOHRSuite\Apps\AwardApp\Models\Award;
use App\ELMOHRSuite\Apps\AwardApp\Models\SlackUser;
use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;
use App\ELMOHRSuite\Core\Views\AbstractSlackView;

class AwardFormView extends AbstractSlackView
{

    protected function rules()
    {
        return [
        ];
    }

    public function template($data)
    {

        /**
         * @var SlackUser $slackUser
         */
        $slackUser = $data['slack_user'];

        return [
            'trigger_id' => $data['trigger_id'],
            'view'       =>
                array(
                    'private_metadata' => Store::AWARD_SUBMISSION_ACTION,
                    'type'             => 'modal',
                    'title'            =>
                        array(
                            'type'  => 'plain_text',
                            'text'  => 'Kudos',
                            'emoji' => true,
                        ),
                    'submit'           =>
                        array(
                            'type'  => 'plain_text',
                            'text'  => 'Send Kudos',
                            'emoji' => true,
                        ),
                    'close'            =>
                        array(
                            'type'  => 'plain_text',
                            'text'  => 'Cancel',
                            'emoji' => true,
                        ),
                    'blocks'           =>
                        [
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
                                            ':first_place_medal: *High Performance Balance* : ' . SlackMessageFormatter::inlineBoldText($slackUser->high_performance_balance) . ' vote(s)'

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
                                            ':trophy: *Employee of The Month Balance* : ' . SlackMessageFormatter::inlineBoldText($slackUser->employee_of_the_month_balance) . ' vote(s)'

                                    ]
                            ],
                            [
                                'type' => 'divider'
                            ],
                            [
                                'block_id' => 'category',
                                'type'     => 'input',
                                'element'  => [
                                    'type'        => 'static_select',
                                    'placeholder' => [
                                        'type'  => 'plain_text',
                                        'text'  => 'Category',
                                        'emoji' => true
                                    ],
                                    'options'     => [
                                        [
                                            'text'  => [
                                                'type'  => 'plain_text',
                                                'text'  => ':cookie: Team Recognition',
                                                'emoji' => true
                                            ],
                                            'value' => Award::TEAM_RECOGNITION
                                        ],
                                        [
                                            'text'  => [
                                                'type'  => 'plain_text',
                                                'text'  => ':first_place_medal: High Performer',
                                                'emoji' => true
                                            ],
                                            'value' => Award::HIGH_PERFORMANCE
                                        ],
                                        [
                                            'text'  => [
                                                'type'  => 'plain_text',
                                                'text'  => ':trophy: Employee of the Month',
                                                'emoji' => true
                                            ],
                                            'value' => Award::EMPLOYEE_OF_THE_MONTH
                                        ]
                                    ],
                                    'action_id'   => 'category'
                                ],
                                'label'    => [
                                    'type'  => 'plain_text',
                                    'text'  => 'Category',
                                    'emoji' => true
                                ]
                            ],
                            [
                                'block_id' => 'user',
                                'type'     => 'input',
                                'element'  => [
                                    'type'        => 'users_select',
                                    'placeholder' => [
                                        'type'  => 'plain_text',
                                        'text'  => 'Select a user',
                                        'emoji' => true
                                    ],
                                    'action_id'   => 'user'
                                ],
                                'label'    => [
                                    'type'  => 'plain_text',
                                    'text'  => 'Who',
                                    'emoji' => true
                                ]
                            ],
                            [
                                'block_id' => 'quantity',
                                'type'     => 'input',
                                'element'  => [
                                    'type'        => 'plain_text_input',
                                    'placeholder' => [
                                        'type'  => 'plain_text',
                                        'text'  => 'Quantity',
                                        'emoji' => true
                                    ],
                                    'action_id'   => 'quantity',
                                ],
                                'label'    => [
                                    'type'  => 'plain_text',
                                    'text'  => 'Quantity ',
                                    'emoji' => true
                                ]
                            ],
                            [
                                'block_id' => 'reason',
                                'type'     => 'input',
                                'element'  => [
                                    'type'        => 'plain_text_input',
                                    'multiline'   => true,
                                    'placeholder' => [
                                        'type'  => 'plain_text',
                                        'text'  => 'I am giving this person kudos because...',
                                        'emoji' => true
                                    ],
                                    'action_id'   => 'reason'
                                ],
                                'label'    => [
                                    'type'  => 'plain_text',
                                    'text'  => 'Reason',
                                    'emoji' => true
                                ]
                            ]
                        ]
                )
        ];
    }
}