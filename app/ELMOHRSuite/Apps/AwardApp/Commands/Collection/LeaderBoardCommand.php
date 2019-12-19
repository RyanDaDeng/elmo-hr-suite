<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:16 PM
 */

namespace App\ELMOHRSuite\Apps\AwardApp\Commands\Collection;

use App\ELMOHRSuite\Apps\AwardApp\Commands\AbstractAwardsCommandBase;
use App\ELMOHRSuite\Apps\AwardApp\Services\AwardService;
use App\ELMOHRSuite\Core\Api\SlackClientApi;
use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LeaderBoardCommand extends AbstractAwardsCommandBase
{

    /**
     * @var string $name
     */
    protected $commandName = 'ranking';

    /**
     * @var string $description
     */
    protected $description = 'Check ranking result.';

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
        Log::info('ss');
        $service = new AwardService();
        $data    = $service->getTopFiveCookies();

        $slackApi = SlackClientApi::instance(
            config('slack.awards.client_access_token')
        );

        $result = [];
        foreach ($data as $item) {

            try {
                $payload  = $slackApi->getUserInfo($item->receiver);
                $row      = [
                    'total'    => $item->total,
                    'receiver' => $item->receiver,
                    'image'    => $payload['user']['profile']['image_32']
                ];
                $result[] = $row;
            } catch (\Exception $e) {
                Log::error($e);
                continue;
            }

        }


        $blocks = [
            [
                'type' => 'section',
                'text' =>
                    [
                        'type' => 'mrkdwn',
                        'text' => '*Leaderboard*'
                    ]
            ],
            [
                'type' => 'divider'
            ],
        ];


        foreach (collect($result)->sortByDesc('total') as $key => $item) {
            $blocks[] =
                [
                    'type'      => 'section',
                    'text'      =>
                        [
                            'type' => "mrkdwn",
                            'text' => SlackMessageFormatter::withParagraphs(
                                SlackMessageFormatter::mentionUserId($item['receiver']),
                                ' :cookie: ' . SlackMessageFormatter::inlineBoldText($item['total']))
                        ],
                    'accessory' => [
                        'type'      => 'image',
                        'image_url' => $item['image'],
                        'alt_text'  => 'profile image'
                    ]

                ];

            $blocks[] = [
                'type' => 'divider'
            ];
        }

        return [
            'text'   => 'The awards you received:',
            'blocks' => $blocks
        ];
    }
}