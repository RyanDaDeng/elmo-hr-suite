<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:50 PM
 */

namespace App\ELMOHRSuite\Apps\LeaveApp\Views;


use App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\Store;
use App\ELMOHRSuite\Core\Views\AbstractSlackView;
use Ramsey\Uuid\Uuid;

class LeaveFormView extends AbstractSlackView
{

    protected function rules()
    {
        return [
            'start_date' => ['required', 'max:150', 'min:0'],
          //  'end_date' => ['required', 'date_format:Y-m-d H:i', 'after:start_date', 'max:150', 'min:0'],
            'reason' => ['required', 'max:500', 'min:0']
        ];
    }

    public function template($data)
    {

        return [
            'trigger_id' => $data['trigger_id'],
            'view' =>
                array(
                    'private_metadata' => Store::LEAVE_SUBMISSION_ACTION,
                    'type' => 'modal',
                    'title' =>
                        array(
                            'type' => 'plain_text',
                            'text' => 'Leave Request',
                            'emoji' => true,
                        ),
                    'submit' =>
                        array(
                            'type' => 'plain_text',
                            'text' => 'Submit',
                            'emoji' => true,
                        ),
                    'close' =>
                        array(
                            'type' => 'plain_text',
                            'text' => 'Cancel',
                            'emoji' => true,
                        ),
                    'blocks' =>
                    //https://api.slack.com/reference/block-kit/blocks#input
                        [
                            [
                                'block_id' => 'team',
                                'type' => 'input',
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'Team'
                                ],
                                'element' => [
                                    'action_id' => 'team',
                                    'type' => 'static_select',
                                    'options' => [
                                        [
                                            'text' => [
                                                'type' => 'plain_text',
                                                'text' => 'Hire'
                                            ],
                                            'value' => 'ER'
                                        ],
                                        [
                                            'text' => [
                                                'type' => 'plain_text',
                                                'text' => 'RTA'
                                            ],
                                            'value' => 'RTA'
                                        ],
                                        [
                                            'text' => [
                                                'type' => 'plain_text',
                                                'text' => 'CRT'
                                            ],
                                            'value' => 'CRT'
                                        ]
                                    ]
                                ],

                            ],
                            [
                                'block_id' => 'manager',
                                'type' => 'input',
                                'element' => [
                                    'action_id' => 'manager',
                                    'type' => 'users_select',
                                    'placeholder' => [
                                        'type' => 'plain_text',
                                        'text' => 'Select a user',
                                        'emoji' => true
                                    ]
                                ],
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'Manager',
                                    'emoji' => true
                                ]
                            ],
                            [
                                'block_id' => 'leave_type',
                                'type' => 'input',
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'Leave Type'
                                ],
                                'element' => [
                                    'action_id' => 'leave_type',
                                    'type' => 'static_select',
                                    'options' => [
                                        [
                                            'text' => [
                                                'type' => 'plain_text',
                                                'text' => ':beach_with_umbrella: Annual leave '
                                            ],
                                            'value' => 'AL'
                                        ],
                                        [
                                            'text' => [
                                                'type' => 'plain_text',
                                                'text' => ':hospital: Sick leave'
                                            ],
                                            'value' => 'SL'
                                        ],
                                        [
                                            'text' => [
                                                'type' => 'plain_text',
                                                'text' => ':house: Work from home leave'
                                            ],
                                            'value' => 'HL'
                                        ]
                                    ]
                                ],

                            ],
                            [
                                'block_id' => 'start_date',
                                'type' => 'input',
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'Start From'
                                ],
                                'element' => [
                                    'action_id' => 'start_date',
                                    'type' => 'plain_text_input',
                                    'initial_value' => date('d/m/Y'),
                                    "placeholder" => [
                                        "type" => "plain_text",
                                        "text" => "e.g. ". date('d/m/Y')
                                      ],
                                ]

                            ],
                            [
                                'block_id' => 'days',
                                'type' => 'input',
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'Days'
                                ],
                                'element' => [
                                    'action_id' => 'days',
                                    'type' => 'plain_text_input',
                                    'initial_value' => '1',

                                ]

                            ],
                            [
                                'block_id' => 'reason',
                                'type' => 'input',
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'Reason'
                                ],
                                'element' => [
                                    'action_id' => 'reason',
                                    'type' => 'plain_text_input',
                                    "multiline" => true,
                                    "placeholder" => [
                                        "type" => "plain_text",
                                        "text" => "Please provide your reason"
                                    ]

                                ]

                            ]
                        ]
                )
        ];
    }
}
