<?php


namespace App\ELMOHRSuite\AwardApp\Commands;


use App\ELMOHRSuite\AwardApp\Commands\Collection\CookiesBalanceCommand;
use App\ELMOHRSuite\AwardApp\Commands\Collection\CookiesYouReceivedCommand;
use App\ELMOHRSuite\AwardApp\Commands\Collection\CookiesYouSentCommand;
use App\ELMOHRSuite\AwardApp\Commands\Collection\LeaderBoardCommand;
use App\ELMOHRSuite\Core\Commands\AbstractSlackGroupCommand;

class AwardsGroupCommand extends AbstractSlackGroupCommand
{

    protected $groupCommandName = 'elmo-awards';
    /**
     * @var array $commands
     */
    protected $commands = [
        CookiesYouSentCommand::class,
        CookiesYouReceivedCommand::class,
        LeaderBoardCommand::class,
        CookiesBalanceCommand::class
    ];

}