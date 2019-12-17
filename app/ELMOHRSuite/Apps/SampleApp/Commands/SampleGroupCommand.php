<?php


namespace App\ELMOHRSuite\Apps\SampleApp\Commands;

use App\ELMOHRSuite\Apps\SampleApp\Commands\Collection\SampleCommand;
use App\ELMOHRSuite\Core\Commands\AbstractSlackGroupCommand;

class SampleGroupCommand extends AbstractSlackGroupCommand
{

    protected $groupCommandName = 'sample';
    /**
     * @var array $commands
     */
    protected $commands = [
        SampleCommand::class
    ];
}