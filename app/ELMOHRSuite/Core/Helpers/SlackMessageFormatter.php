<?php
declare(strict_types=1);

namespace App\ELMOHRSuite\Core\Helpers;


class SlackMessageFormatter
{
    /**
     * @param string $userId
     * @return string
     */
    public static function mentionUserId(string $userId): string
    {
        return '<@' . $userId . '>';
    }

    /**
     * @param string $channelId
     * @return string
     */
    public static function mentionChannel(string $channelId): string
    {
        return '<#' . $channelId . '>';
    }

    /**
     * @param $name
     * @param $url
     * @return string
     */
    public static function hyperLink($name, $url): string
    {
        return '<' . $url . '|' . $name . '>';
    }

    public static function mentionUserIds(array $userIds): string
    {
        $list = collect($userIds)->map(function ($item) {
            return self::mentionUserId($item);
        })->toArray();
        return implode($list, ',');
    }

    /**
     * @param string $message
     * @return string
     */
    public static function italic(string $message): string
    {
        return '_' . $message . '_';
    }

    /**
     * @param string $message
     * @return string
     */
    public static function bold(string $message): string
    {
        return '*' . $message . '*';
    }

    /**
     * @param string $message
     * @return string
     */
    public static function inlineText(string $message): string
    {
        return '`' . $message . '`';
    }

    /**
     * @param string $message
     * @return string
     */
    public static function inlineBoldText(string $message): string
    {
        return '*`' . $message . '`*';
    }


    /**
     * @param string $message
     * @return string
     */
    public static function inlineBoldItalicText(string $message): string
    {
        return '_*`' . $message . '`*_';
    }

    /**
     * @param string $message
     * @return string
     */
    public static function blocksOfText(string $message): string
    {
        return '`' . $message . '`';
    }

    /**
     * @param string $message
     * @return string
     */
    public static function delete(string $message): string
    {
        return '~' . $message . '~';
    }

    /**
     * @param string $message
     * @return string
     */
    public static function quote(string $message): string
    {
        return '>' . $message;
    }

    /**
     * @param string $message
     * @return string
     */
    public static function quotes(string $message): string
    {
        return '>>>' . $message;
    }

    /**
     * @param array $messages
     * @return string
     */
    public static function listOfUnorderedMessage(array $messages): string
    {

        $res = '';
        foreach ($messages as $message) {
            $res .= '• ' . $message . "\n";
        }
        return $res;
    }

    /**
     * @param array $messages
     * @return string
     */
    public static function listOfOrderedMessage(array $messages): string
    {

        $res = '';
        foreach ($messages as $key => $message) {
            $res .= self::bold($key + 1) . '. ' . $message . "\n";
        }
        return $res;
    }

    /**
     * @param array $messages
     * @return string
     */
    public static function paragraphs(array $messages): string
    {
        $res = '';

        foreach ($messages as $message) {
            $res .= $message . "\n";
        }

        return $res;
    }

    /**
     * @param array $messages
     * @return string
     */
    public static function withParagraphs(...$messages): string
    {
        $res = '';

        foreach ($messages as $message) {
            $res .= $message . "\n";
        }

        return $res;
    }


    /**
     * @param array $segments
     * @return string
     */
    public static function sentence(array $segments): string
    {
        $res = '';
        foreach ($segments as $message) {
            $res .= $message . ' ';
        }
        return $res;
    }
}