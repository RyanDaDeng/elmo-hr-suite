<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:16 PM
 */

namespace App\ELMOHRSuite\Apps\LeaveApp\Commands;


use App\ELMOHRSuite\Apps\LeaveApp\Commands\Collection\OpenLeaveCommand;
use App\ELMOHRSuite\Core\Commands\AbstractSlackGroupCommand;

class LeaveGroupCommand extends AbstractSlackGroupCommand
{

    /**
     * @var array $commands
     */
    protected $commands = [
        OpenLeaveCommand::class
    ];


}