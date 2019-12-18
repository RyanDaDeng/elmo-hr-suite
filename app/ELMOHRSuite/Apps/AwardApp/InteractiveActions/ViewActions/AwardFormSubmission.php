<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 8:03 PM
 */

namespace App\ELMOHRSuite\Apps\AwardApp\InteractiveActions\ViewActions;

use App\ELMOHRSuite\Core\InteractiveManager\AbstractInteractive;

class AwardFormSubmission extends AbstractInteractive
{

    public function validate()
    {
        return true;
    }

    public function handle()
    {
//        $api = SlackClientApi::instance(
//            config('slack.leave.client_access_token')
//        );
//
//        $dialog = new LeaveFormView();
//        $res = $dialog->validate($this->payload);
//        if ($res->isFailed()) {
//            return JsonResponse::create([
//                'response_action' => 'errors',
//                'errors' => $res->getErrors()
//            ], 200);
//        }
//
//        Log::info($res->getData());
//
//        return JsonResponse::create(
//            ["response_action" => "clear"],
//            200);
    }

}