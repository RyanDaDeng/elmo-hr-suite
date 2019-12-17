<?php

namespace App\ELMOHRSuite\Core\Events;

use Illuminate\Http\JsonResponse;

abstract class AbstractSlackEvent
{
    /**
     * @var array $payload
     */
    protected $payload;

    /**
     * @var array $event
     */
    protected $event;


    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }


    abstract public function validate(): bool;


    public function execute()
    {
        $this->handle();
    }

    abstract public function handle(): void;


    public function response(): JsonResponse
    {
        return JsonResponse::create([], 200);
    }
}