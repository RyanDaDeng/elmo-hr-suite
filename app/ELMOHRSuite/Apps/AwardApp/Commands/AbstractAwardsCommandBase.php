<?php


namespace App\ELMOHRSuite\Apps\AwardApp\Commands;


use App\ELMOHRSuite\Apps\AwardApp\Models\SlackUser;
use App\ELMOHRSuite\Apps\AwardApp\Services\SlackUserService;
use App\ELMOHRSuite\Core\Commands\AbstractSlackCommand;

use Illuminate\Support\Facades\Log;

abstract class AbstractAwardsCommandBase extends AbstractSlackCommand
{

    /**
     * @var SlackUser $slackUser
     */
    protected $slackUser;

    public function __construct($payload)
    {
        Log::info($payload);
        parent::__construct($payload);
        $this->auth();
    }

    private function auth()
    {
        $service = new SlackUserService();
        $this->slackUser = $service->getSlackUser($this->payload['user_id']);
    }
}