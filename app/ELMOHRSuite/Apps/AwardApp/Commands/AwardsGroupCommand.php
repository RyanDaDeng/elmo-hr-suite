<?php


namespace App\ELMOHRSuite\Apps\AwardApp\Commands;

use App\ELMOHRSuite\Apps\AwardApp\Commands\Collection\CookiesBalanceCommand;
use App\ELMOHRSuite\Apps\AwardApp\Commands\Collection\CookiesYouReceivedCommand;
use App\ELMOHRSuite\Apps\AwardApp\Commands\Collection\CookiesYouSentCommand;
use App\ELMOHRSuite\Apps\AwardApp\Commands\Collection\LeaderBoardCommand;
use App\ELMOHRSuite\Apps\AwardApp\Commands\Collection\OpenAwardCommand;
use App\ELMOHRSuite\Core\Commands\AbstractSlackGroupCommand;

class AwardsGroupCommand extends AbstractSlackGroupCommand
{

     protected $groupCommandName = 'elmo-awards';
    /**
     * @var array $commands
     */
    protected $commands = [
        CookiesYouReceivedCommand::class,
        CookiesYouSentCommand::class,
        LeaderBoardCommand::class,
        CookiesBalanceCommand::class,
        OpenAwardCommand::class
    ];

}