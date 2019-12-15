<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 5:19 PM
 */

namespace App\ELMOHRSuite\Core\InteractiveManager;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

abstract class AbstractInteractive
{

    /**
     * @var array $payload
     */
    protected $payload;

    /**
     * AbstractInteractive constructor.
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return bool
     */
    abstract public function validate();


    /**
     * @return array
     */
    abstract public function handle();


    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    public function process()
    {
        if ($this->validate()) {
            return $this->handle();
        }
    }

}