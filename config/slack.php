<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 5:06 PM
 */

return [
    'awards' => [
        'client_access_token' => env('AWARDS_SLACK_CLIENT_ACCESS_TOKEN', ''),
        'bot_access_token'    => env('AWARDS_SLACK_BOT_ACCESS_TOKEN', ''),
        'bot_user_id'         => 'URUC25N1L'
    ],
    'leave'  => [
        'client_access_token' => env('LEAVE_SLACK_CLIENT_ACCESS_TOKEN', ''),
        'bot_access_token'    => env('LEAVE_SLACK_BOT_ACCESS_TOKEN', ''),
        'bot_user_id'         => ''
    ]
];