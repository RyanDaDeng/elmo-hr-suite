<?php


namespace App\ELMOHRSuite\Apps\AwardApp\Services;

use App\ELMOHRSuite\Apps\AwardApp\Models\Award;
use App\ELMOHRSuite\Apps\AwardApp\Models\SlackUser;

class AwardService
{
    private function sendAward(
        $data
    ) {
        return Award::query()
            ->create(
                [
                    'category' => $data['category'],
                    'quantity' => $data['quantity'],
                    'sender'   => $data['sender'],
                    'receiver' => $data['receiver'],
                    'sent_at'  => $data['sent_at'],
                    'reason'   => $data['reason']
                ]
            )
            ->save();
    }

    public function sendTeamRecognition(
        SlackUser $sender,
        $receiver,
        $sentAt,
        $quantity,
        $reason
    ) {
        // send award
        return $this->sendAward([
            'quantity' => $quantity,
            'sender'   => $sender,
            'receiver' => $receiver,
            'sent_at'  => $sentAt,
            'reason'   => $reason,
            'category' => Award::TEAM_RECOGNITION
        ]);
    }

    public function sendHighPerformance(
        SlackUser $sender,
        $receiver,
        $sentAt,
        $quantity,
        $reason
    ) {

        // check enough balance
        if ($sender->hasHighPerformanceBalance($quantity)) {
            return false;
        }
        // decrement balance
        $sender->newQuery()->decrement('high_performance_balance');

        // send award
        return $this->sendAward([
            'quantity' => $quantity,
            'sender'   => $sender,
            'receiver' => $receiver,
            'sent_at'  => $sentAt,
            'reason'   => $reason,
            'category' => Award::HIGH_PERFORMANCE
        ]);
    }

    public function sendEmployeeOfMonth(
        SlackUser $sender,
        $receiver,
        $sentAt,
        $quantity,
        $reason
    ) {

        // check enough balance
        if ($sender->hasEnoughEmployeeOfTheMonthBalance($quantity)) {
            return false;
        }
        // decrement balance
        $sender->newQuery()->decrement('employee_of_the_month_balance');

        // send award
        return $this->sendAward([
            'quantity' => $quantity,
            'sender'   => $sender,
            'receiver' => $receiver,
            'sent_at'  => $sentAt,
            'reason'   => $reason,
            'category' => Award::EMPLOYEE_OF_THE_MONTH
        ]);
    }

    /**
     * @param $slackUserId
     * @return Award[]
     */
    public function showMySent($slackUserId)
    {
        return Award::query()->where('sender', $slackUserId)->get();
    }

    /**
     * @param $slackUserId
     * @return Award[]
     */
    public function showMyReceives($slackUserId)
    {
        return Award::query()->where('receiver', $slackUserId)->get();

    }


    public function getTotalTeamRecognition($slackUserId)
    {
        return Award::query()
            ->where('receiver', $slackUserId)
            ->where('category', Award::TEAM_RECOGNITION)
            ->count();

    }

    public function getTotalEmployeeOfTheMonth($slackUserId)
    {
        return Award::query()
            ->where('receiver', $slackUserId)
            ->where('category', Award::EMPLOYEE_OF_THE_MONTH)
            ->count();
    }

    public function getTotalHighPerformance($slackUserId)
    {
        return Award::query()
            ->where('receiver', $slackUserId)
            ->where('category', Award::HIGH_PERFORMANCE)
            ->count();
    }

    public function getTopFiveCookies()
    {


    }
}