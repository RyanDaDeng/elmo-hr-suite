<?php


namespace App\ELMOHRSuite\Apps\AwardApp\Services;

use App\ELMOHRSuite\Apps\AwardApp\Models\Award;
use App\ELMOHRSuite\Apps\AwardApp\Models\SlackUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AwardService
{
    public function send(
        $category,
        $sender,
        $receiver,
        $quantity,
        $reason
    ) {
        $sendAt = Carbon::now()->format('Y-m-d H:i:s');

        switch ($category) {
            case Award::TEAM_RECOGNITION:
                {
                    return $this->sendTeamRecognition(
                        $sender,
                        $receiver,
                        $sendAt,
                        $quantity,
                        $reason
                    );
                }
            case Award::EMPLOYEE_OF_THE_MONTH:
                {
                    Log::info('------');
                    return $this->sendEmployeeOfMonth(
                        $sender,
                        $receiver,
                        $sendAt,
                        $quantity,
                        $reason
                    );
                }
            case Award::HIGH_PERFORMANCE:
                {
                    return $this->sendHighPerformance(
                        $sender,
                        $receiver,
                        $sendAt,
                        $quantity,
                        $reason
                    );
                }
        }
    }

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
        $sender,
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

    /**
     * @param $slackUserId
     * @return SlackUser
     */
    public function getSlackUser($slackUserId)
    {
        return SlackUser::query()->where('slack_user_id', $slackUserId)->first();
    }

    public function sendHighPerformance(
        $sender,
        $receiver,
        $sentAt,
        $quantity,
        $reason
    ) {

        $slackSender = $this->getSlackUser($sender);

        // check enough balance
        if (!$slackSender->hasHighPerformanceBalance($quantity)) {
            return false;
        }

        $slackSender->high_performance_balance = $slackSender->high_performance_balance - $quantity;
        $slackSender->save();
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
        $sender,
        $receiver,
        $sentAt,
        $quantity,
        $reason
    ) {
        $slackSender = $this->getSlackUser($sender);
        // check enough balance
        if (!$slackSender->hasEnoughEmployeeOfTheMonthBalance($quantity)) {
            return false;
        }
        // decrement balance
        $slackSender->employee_of_the_month_balance = $slackSender->employee_of_the_month_balance - $quantity;
        $slackSender->save();

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
        return Award::query()->where('sender', $slackUserId)->limit(10)->get()->sortByDesc('id');
    }

    /**
     * @param $slackUserId
     * @return Award[]
     */
    public function showMyReceives($slackUserId)
    {
        return Award::query()->where('receiver', $slackUserId)->limit(10)->get()->sortByDesc('id');

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
        return DB::table('awards')
            ->select(
                DB::raw('sum(quantity) as total'),
                'receiver'
            )
            ->where('category', Award::TEAM_RECOGNITION)
            ->groupBy('receiver')
            ->get()
            ->sortByDesc('total')
            ->take(5)
            ->toArray();
    }
}