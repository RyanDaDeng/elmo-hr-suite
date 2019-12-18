<?php

namespace App\ELMOHRSuite\Apps\AwardApp\Views;

use App\ELMOHRSuite\Apps\AwardApp\InteractiveActions\Store;
use App\ELMOHRSuite\Core\Views\AbstractSlackView;

class AwardFormView extends AbstractSlackView
{

    protected function rules()
    {
        return [
//            'start_date' => ['required', 'date_format:Y-m-d H:i', 'max:150', 'min:0'],
//            'end_date' => ['required', 'date_format:Y-m-d H:i', 'after:start_date', 'max:150', 'min:0'],
//            'reason' => ['required', 'max:500', 'min:0']
        ];
    }

    public function template($data)
    {
        return [
            'trigger_id' => $data['trigger_id'],
            'view' =>
                array(
                    'private_metadata' => Store::AWARD_SUBMISSION_ACTION,
                    'type' => 'modal',
                    'title' =>
                        array(
                            'type' => 'plain_text',
                            'text' => 'Kudos',
                            'emoji' => true,
                        ),
                    'submit' =>
                        array(
                            'type' => 'plain_text',
                            'text' => 'Send Kudos',
                            'emoji' => true,
                        ),
                    'close' =>
                        array(
                            'type' => 'plain_text',
                            'text' => 'Cancel',
                            'emoji' => true,
                        ),
                    'blocks' =>
                        [
                            [
                                'type' => 'input',
                                'element' => [
                                    'type' => 'static_select',
                                    'placeholder' => [
                                        'type' => 'plain_text',
                                        'text' => 'Category',
                                        'emoji' => true
                                    ],
                                    'options' => [
                                        [
                                            'text' => [
                                                'type' => 'plain_text',
                                                'text' => 'Team Recognition',
                                                'emoji' => true
                                            ],
                                            'value' => 'value-1'
                                        ],
                                        [
                                            'text' => [
                                                'type' => 'plain_text',
                                                'text' => 'High Performer',
                                                'emoji' => true
                                            ],
                                            'value' => 'value-2'
                                        ],
                                        [
                                            'text' => [
                                                'type' => 'plain_text',
                                                'text' => 'EOTM',
                                                'emoji' => true
                                            ],
                                            'value' => 'value-2'
                                        ]
                                    ]
                                ],
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'Category',
                                    'emoji' => true
                                ]
                            ],
                            [
                                'type' => 'input',
                                'element' => [
                                    'type' => 'users_select',
                                    'placeholder' => [
                                        'type' => 'plain_text',
                                        'text' => 'Select a user',
                                        'emoji' => true
                                    ]
                                ],
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'Who',
                                    'emoji' => true
                                ]
                            ],
                            [
                                'type' => 'input',
                                'element' => [
                                    'type' => 'plain_text_input'
                                ],
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'Quantity',
                                    'emoji' => true
                                ]
                            ],
                            [
                                'type' => 'input',
                                'element' => [
                                    'type' => 'plain_text_input',
                                    'multiline' => true
                                ],
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'Reason',
                                    'emoji' => true
                                ]
                            ]
                        ]
                )
        ];
    }
}