<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:16 PM
 */

namespace App\ELMOHRSuite\LeaveApp\Commands;


use App\ELMOHRSuite\Core\Api\SlackClientApi;
use App\ELMOHRSuite\Core\Commands\AbstractSlackCommand;
use App\ELMOHRSuite\LeaveApp\Views\LeaveFormView;
use Illuminate\Support\Facades\Log;

class OpenLeaveCommand extends AbstractSlackCommand
{

    /**
     * @var string $name
     */
    protected $commandName = 'leave';

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
        $leaveFormView = new LeaveFormView();

        SlackClientApi::instance(
            config('slack.client_access_token')
        )->openViews(
            $leaveFormView->template([
                'trigger_id' => $this->payload['trigger_id']
            ])
        );

        return '';
    }
}