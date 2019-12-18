<?php

namespace App\ELMOHRSuite\Apps\AwardApp\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $high_performance_balance
 * @property $employee_of_the_month_balance
 * @property $id
 * @property $slack_user_id
 * @property
 * Class SlackUser
 * @package App
 */
class SlackUser extends Model
{
    //
    protected $guarded = [];


    public function hasEnoughEmployeeOfTheMonthBalance($want = 1)
    {
        return $this->employee_of_the_month_balance >= 1;
    }

    public function hasHighPerformanceBalance($want = 1)
    {
        return $this->high_performance_balance >= 1;
    }
}
