<?php

namespace App\ELMOHRSuite\Core\Events;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

abstract class AbstractSlackEventManager
{
    /**
     * @var array $payload
     */
    protected $payload;

    /**
     * @var $eventCallbacks
     */
    protected $events = [];


    /**
     * SlackEventManager constructor.
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;;
    }


    /**
     * @param $parentType
     * @param $index
     * @return array
     */
    private function getEventCollection($parentType, $index)
    {
        if (isset($this->events[$parentType][$index])) {
            return $this->events[$parentType][$index];
        }

        return [];
    }


    public function shouldSkip($parentType, $index)
    {

        if (isset($this->events[$parentType]) && !isset($this->events[$parentType][$index])) {
            return true;
        }

        if (isset($this->events[$parentType]) && $this->events[$parentType] === false) {
            return true;
        }

        if (isset($this->events[$parentType][$index]) && $this->events[$parentType][$index] === false) {
            return true;
        }

        return false;
    }

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        try {
            Log::info('----- Event -----');
            Log::info(json_encode($this->payload, JSON_PRETTY_PRINT));

            $parentType = $this->payload['type'];

            if ($parentType === 'url_verification') {
                return JsonResponse::create(
                    [
                        'challenge' => $this->payload['challenge']
                    ],
                    200);
            }


            $event        = $this->payload['event'];
            $eventType    = $event['type'];
            $eventSubType = isset($event['subtype']) ? $event['subtype'] : null;

            $index = $eventType;
            if ($eventSubType) {
                $index .= '.' . $eventSubType;
            }

            if ($this->shouldSkip($parentType, $index)) {
                Log::info('This event is skipped');
                return JsonResponse::create([], 200);
            }

            $events = $this->getEventCollection($parentType, $index);
            foreach ($events as $eventHandler) {
                if (is_array($eventHandler)) {
                    continue;
                }
                Log::info($eventHandler);

                /**
                 * @var AbstractSlackEvent $obj
                 */
                $obj = new $eventHandler(
                    $this->payload
                );
                if ($this->processEvent($obj)) {
                    return $obj->response();
                }
            }

            return JsonResponse::create([], 200);
        } catch (\Exception $e) {
            return JsonResponse::create([], 200);
        }
    }


    private function processEvent(AbstractSlackEvent $obj)
    {
        try {
            if (!$obj->validate()) {
                Log::info('Validation not pass');
                return false;
            }

            $obj->execute();
            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return true;
        }
    }
}