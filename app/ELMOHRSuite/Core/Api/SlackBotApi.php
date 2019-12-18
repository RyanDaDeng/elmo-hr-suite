<?php


namespace App\ELMOHRSuite\Core\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Slackit\LaravelSlackSDK\Services\Core\Messages\SlackMessage;


class SlackBotApi
{

    /**
     * @var self
     */
    protected static $instance = null;

    /**
     * @param $accessToken
     * @return self
     */
    public static function instance($accessToken)
    {
        if (self::$instance === null) {
            return new self($accessToken);
        } else {
            return self::$instance;
        }
    }

    /**
     * @var Client $http
     */
    private $http;


    public function __construct($accessToken)
    {
        $this->http = new Client(
            [
                'base_uri' => 'https://slack.com/api/',
                'headers'  => [
                    'Authorization' => "Bearer " . $accessToken,
                    'Content-Type'  => 'application/json;charset=UTF-8',
                    'Accept'        => 'application/json',
                ]

            ]
        );
    }

    public function addEmojiToMessage($emoji, $channel, $timestamp)
    {
        $res = $this->http->request(
            'post',
            'reactions.add', [
            'json' => [
                'name'      => $emoji,
                'channel'   => $channel,
                'timestamp' => $timestamp
            ]
        ]);
        Log::info(json_decode($res->getBody()->getContents(), 1));
        return json_decode($res->getBody()->getContents(), 1);
    }


    public function removeEmojiFromMessage($emoji, $channel, $timestamp)
    {
        $res = $this->http->request(
            'post',
            'reactions.remove', [
            'json' => [
                'name'      => $emoji,
                'channel'   => $channel,
                'timestamp' => $timestamp
            ]
        ]);
        //   Log::info(json_decode($res->getBody()->getContents(), 1));
        return json_decode($res->getBody()->getContents(), 1);
    }

    /**
 * https://api.slack.com/methods/chat.postMessage
 * @param array $message
 * @return mixed
 */
    public function postMessage(array $message)
    {
        $res = $this->http->request(
            'post',
            'chat.postMessage', [
            'json' => $message
        ]);
//        Log::info($slackMessage->getBlocks()->getNumberOfBlocks());
        Log::info(json_decode($res->getBody()->getContents(), 1));
        return json_decode($res->getBody()->getContents(), 1);
    }

    /**
     * https://api.slack.com/methods/chat.postMessage
     * @param array $message
     * @return mixed
     */
    public function updateMessageByUrl(array $message, $url)
    {
        echo $url;
        $res = $this->http->request(
            'post',
            $url, [
            'json' => $message
        ]);
//        Log::info($slackMessage->getBlocks()->getNumberOfBlocks());
        Log::info(json_decode($res->getBody()->getContents(), 1));
        return json_decode($res->getBody()->getContents(), 1);
    }


    public function chatGetPermalink($channel, $messageTs)
    {
        $res = $this->http->request(
            'get',
            'chat.getPermalink', [
            'query' => [
                'channel'    => $channel,
                'message_ts' => $messageTs
            ]
        ]);
        // Log::info(json_decode($res->getBody()->getContents(), 1));
        return json_decode($res->getBody()->getContents(), 1);
    }
}
