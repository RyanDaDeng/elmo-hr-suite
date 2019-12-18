<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:05 PM
 */

namespace App\ELMOHRSuite\Core\Commands;


use App\ELMOHRSuite\Core\Helpers\SlackMessageFormatter;

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
     * @var string $description
     */
    protected $description = '';

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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return array|mixed
     */
    public function handle()
    {
        return $this->process();

    }

    public function menu($groupCommandName = '')
    {
        return [
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => SlackMessageFormatter::withParagraphs(
                        SlackMessageFormatter::inlineBoldText('/' . $groupCommandName . ' ' . $this->getCommandName()),
                        '/' . $groupCommandName . ' ' . $this->getCommandName() . ': ' . $this->getDescription()
                    )
                ]
            ],
            [
                'type' => 'divider'
            ]
        ];
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
            throw $e;
            return 'Server has some errors.';
        }
    }

}