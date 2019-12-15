<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:16 PM
 */

namespace App\ELMOHRSuite\LeaveApp\GroupCommands;


use App\ELMOHRSuite\Core\Commands\AbstractSlackGroupCommand;
use App\ELMOHRSuite\LeaveApp\Commands\OpenLeaveCommand;


class LeaveGroupCommand extends AbstractSlackGroupCommand
{

    /**
     * @var array $commands
     */
    protected $commands = [
        OpenLeaveCommand::class
    ];


}