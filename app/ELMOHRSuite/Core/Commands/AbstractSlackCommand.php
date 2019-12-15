<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:05 PM
 */

namespace App\ELMOHRSuite\Core\Commands;


abstract class AbstractSlackCommand
{
    /**
     * @var array $payload
     */
    protected $payload = [];

    /**
     * Command Name
     * @var string
     */
    protected $commandName = '';

    /**
     * AbstractSlackCommand constructor.
     * @param $payload
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
     * @return array|mixed
     */
    abstract public function process();

    /**
     * @return string
     */
    public function getCommandName()
    {
        return $this->commandName;
    }

    /**
     * @return array|mixed
     */
    public function handle()
    {
        return $this->process();

    }

    /**
     * @return mixed|string
     */
    public function execute()
    {
        try {
            if (!$this->validate()) {
                return $this->payload['command'] . ' ' . $this->commandName . ' is not valid.';
            }
            return $this->handle();
        } catch (\Exception $e) {
            return 'Server has some errors.';
        }
    }

}