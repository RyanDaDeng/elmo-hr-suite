<?php

namespace App\ELMOHRSuite\Apps\AwardApp\Models;

use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $sender
 * @property $receiver
 * @property $reason
 * @property $category
 * @property $quantity
 * @property $sent_at
 * @property $sender_slack_format
 * @property $receiver_slack_format
 * @property $emoji
 * Class Award
 * @package App
 */
class Award extends Model
{

    const HIGH_PERFORMANCE      = 'High Performance';
    const EMPLOYEE_OF_THE_MONTH = 'Employee of The Month';
    const TEAM_RECOGNITION      = 'Team Recognition';

    public $guarded = [];

    public $appends = [
        'sender_slack_format',
        'receiver_slack_format',
        'emoji'
    ];

    public function getSenderSlackFormatAttribute()
    {
        return SlackMessageFormatter::mentionUserId($this->attributes['sender']);
    }

    public function getReceiverSlackFormatAttribute()
    {
        return SlackMessageFormatter::mentionUserId($this->attributes['receiver']);
    }


    public function getEmojiAttribute()
    {
        return $this->getEmojiByCategory($this->attributes['category']);

    }


    public static function getEmojiByCategory($category)
    {
        switch ($category) {
            case 'High Performance':
                return ':first_place_medal:';
                break;
            case 'Employee of The Month':
                return ':trophy:';
                break;
            case "Team Recognition":
                return ':cookie:';
                break;
        }
        return ':cookie:';
    }
}
