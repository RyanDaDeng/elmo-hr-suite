<?php


namespace App\ELMOHRSuite\Core\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;


class SlackClientApi
{

    /**
     * @var Client $http
     */
    private $http;


    /**
     * @var self
     */
    protected static $instance = null;

    /**
     * @return self
     * @param $accessToken
     */
    public static function instance($accessToken)
    {
        if (self::$instance === null) {
            return new self($accessToken);
        } else {
            return self::$instance;
        }
    }

    public function __construct($accessToken)
    {
        $this->http = new Client(
            [
                'base_uri' => 'https://slack.com/api/',
                'headers' => [
                    'Authorization' => "Bearer " . $accessToken,
                    'Content-Type' => 'application/json;charset=UTF-8',
                    'Accept' => 'application/json',
                ]

            ]
        );
    }


    public function respondToUrl($responseUrl, $data)
    {

        try {
            $http = new Client(
                [
                    'headers' => [
                        'Content-Type' => 'application/json;charset=UTF-8',
                        'Accept' => 'application/json',
                    ]
                ]
            );
            Log::info($data);
            $res = $http->post($responseUrl, ['json' => $data]);
            Log::info(json_decode($res->getBody()->getContents(), 1));
            return json_decode($res->getBody()->getContents(), 1);
        } catch (Exception $e) {
            Log::info($e);
        }
        return false;
    }

    public function getUserInfo($slackUserId){
        $res = $this->http->request(
            'get',
            'users.info', [
            'query' => [
                'user'    => $slackUserId,
            ]
        ]);
     //   Log::info(json_decode($res->getBody()->getContents(), 1));
        return json_decode($res->getBody()->getContents(), 1);
    }

    public function postMessageToChannel($messages)
    {
        $res = $this->http->request(
            'post',
            'chat.postMessage', [
            'json' => $messages
        ]);
        Log::info(json_decode($res->getBody()->getContents(), 1));
        return json_decode($res->getBody()->getContents(), 1);
    }

    public function openViews($data)
    {
        try {
            $res = $this->http->request(
                'post',
                'views.open',
                [
                    'json' => $data
                ]);
            $body = $res->getBody()->getContents();
            Log::error($res->getBody()->getContents());
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function openDialog($dialogData)
    {
        $res = $this->http->request(
            'post',
            'dialog.open',
            [
                'json' => $dialogData
            ]);

        Log::error($res->getBody()->getContents());
    }


}
