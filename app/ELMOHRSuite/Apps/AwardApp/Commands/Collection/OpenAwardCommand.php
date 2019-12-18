<?php

namespace App\ELMOHRSuite\Apps\AwardApp\Commands\Collection;


use App\ELMOHRSuite\Apps\AwardApp\Views\AwardFormView;
use App\ELMOHRSuite\Core\Api\SlackClientApi;
use App\ELMOHRSuite\Core\Commands\AbstractSlackCommand;

class OpenAwardCommand extends AbstractSlackCommand
{

    protected $description = 'create award';
    /**
     * @var string $name
     */
    protected $commandName = 'kudos';

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

        $awardFormView = new AwardFormView();

        SlackClientApi::instance(
            config('slack.awards.client_access_token')
        )->openViews(
            $awardFormView->template([
                                         'trigger_id' => $this->payload['trigger_id']
                                     ])
        );

        return '';
    }
}