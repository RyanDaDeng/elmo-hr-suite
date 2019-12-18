<?php


namespace App\ELMOHRSuite\Apps\AwardApp\Services;

use App\ELMOHRSuite\Apps\AwardApp\Models\SlackUser;

class SlackUserService
{

    public function getSlackUser($slackUserId)
    {
        $user = SlackUser::query()->where('slack_user_id', $slackUserId)->first();

        if (!$user) {
            $user     = new SlackUser();
            $user->slack_user_id = $slackUserId;
            $user->save();
        }

        return $user;

    }

    public function ifUserExists($slackUserId)
    {
        return SlackUser::query()->where('slack_user_id', $slackUserId)->exists();
    }
}