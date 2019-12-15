<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:50 PM
 */

namespace App\ELMOHRSuite\LeaveApp\Views;


use App\ELMOHRSuite\Core\Views\AbstractSlackView;
use App\ELMOHRSuite\LeaveApp\Actions\Store;
use Ramsey\Uuid\Uuid;

class LeaveFormView extends AbstractSlackView
{

    protected function rules()
    {
        return [
            'start_date' => ['required', 'date_format:Y-m-d H:i', 'max:150', 'min:0'],
            'end_date' => ['required', 'date_format:Y-m-d H:i', 'after:start_date', 'max:150', 'min:0'],
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
                                                'text' => 'normal form'
                                            ],
                                            'value' => 'normal'
                                        ]
                                    ]
                                ],

                            ],
                            [
                                'block_id' => 'start_date',
                                'type' => 'input',
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'start_date'
                                ],
                                'element' => [
                                    'action_id' => 'start_date',
                                    'type' => 'plain_text_input'
                                ],

                            ],
                            [
                                'block_id' => 'end_date',
                                'type' => 'input',
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'end_date'
                                ],
                                'element' => [
                                    'action_id' => 'end_date',
                                    'type' => 'plain_text_input'
                                ]

                            ],
                            [
                                'block_id' => 'reason',
                                'type' => 'input',
                                'label' => [
                                    'type' => 'plain_text',
                                    'text' => 'reason'
                                ],
                                'element' => [
                                    'action_id' => 'reason',
                                    'type' => 'plain_text_input',
                                    "multiline" => true
                                ]

                            ]
                        ]
                )
        ];
    }
}