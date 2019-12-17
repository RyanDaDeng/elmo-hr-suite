<?php
declare(strict_types=1);

namespace App\ELMOHRSuite\Core\Helpers;


use Illuminate\Support\Facades\Log;

class SlackMessageParser
{


    public static function countEmoji($emoji, $text)
    {
        preg_match_all('/' . $emoji . '/s', $text, $matches);

        return collect($matches[0])->count();
    }

    public static function isEmojiExists($emoji, $text)
    {
        preg_match('/' . $emoji . '/s', $text, $match);
        return count($match) > 0;
    }

    public static function removeAllMentions($text)
    {
        $text = preg_replace('/\<@(.*?)\>/s', '', $text);
        $text = preg_replace('/\<!channel\>/s', '', $text);
        $text = preg_replace('/\<!here\>/s', '', $text);
        return $text;
    }

    /**
     * @param $text
     * @param array $excludes
     * @return array
     */
    public static function getMentionedUsers($text, $excludes = []): array
    {
        preg_match_all('/\<@(.*?)\>/s', $text, $matches);
        if ($excludes) {
            return array_values(collect($matches[1])->filter(function ($item) use ($excludes) {
                return !in_array($item, $excludes);
            })->unique()->toArray());
        }
        return collect($matches[1])->unique()->toArray();
    }

    /**
     * @param $text
     * @return bool
     */
    public static function isChannelMentioned($text): bool
    {
        preg_match('/\<!channel\>/s', $text, $match);
        return count($match) > 0;
    }

    /**
     * @param $text
     * @return bool
     */
    public static function isHereMentioned($text): bool
    {
        preg_match('/\<!here\>/s', $text, $match);
        return count($match) > 0;
    }

    /**
     * @param $userId
     * @param $text
     * @return bool
     */
    public static function isUserMentioned($userId, $text): bool
    {
        preg_match('/\<@' . $userId . '\>/s', $text, $match);
        return count($match) > 0;
    }
}