<?php

namespace App\ELMOHRSuite\Apps\AwardApp\Commands\Collection;


use App\ELMOHRSuite\Apps\AwardApp\Commands\AbstractAwardsCommandBase;
use App\ELMOHRSuite\Apps\AwardApp\Models\SlackUser;
use App\ELMOHRSuite\Apps\AwardApp\Views\AwardFormView;
use App\ELMOHRSuite\Core\Api\SlackClientApi;

class SearchAwardCommand extends AbstractAwardsCommandBase
{

    protected $description = 'create award';
    /**
     * @var string $name
     */
    protected $commandName = 'send';

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


//        /**
//         * @var SlackUser $a
//         */
//        $a = SlackUser::query()->where('slack_user_id', $this->payload['user_id'])->first();
//        $a->high_performance_balance--;
//        $a->save();
//
//        return $a;
        $awardFormView = new AwardFormView();

        SlackClientApi::instance(
            config('slack.awards.client_access_token')
        )->openViews(
            $awardFormView->template([
                'trigger_id'    => $this->payload['trigger_id'],
                'slack_user' => $this->slackUser
            ])
        );

        return '';
    }
}