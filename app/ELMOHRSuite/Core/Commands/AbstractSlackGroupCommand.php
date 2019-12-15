<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:01 PM
 */

namespace App\ELMOHRSuite\Core\Commands;


use Illuminate\Support\Facades\Log;

class AbstractSlackGroupCommand
{

    /**
     * @var array $payload
     */
    private $payload = [];
    /**
     * @var AbstractSlackCommand[] $commands
     */
    protected $commands = [
    ];

    /**
     * @var array
     */
    private $commandMap = [];

    public function __construct($payload)
    {
        $this->payload = $payload;
        $this->commandMap = $this->resolveCommands($this->commands);
    }

    /**
     * @return mixed|string
     */
    public function execute()
    {
        try {
            return $this->run();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @return mixed|string
     * @throws \Exception
     */
    public function run()
    {

        $command = $this->getCommand($this->payload['text']);
        return $command->execute();
    }


    /**
     * @param $commands
     * @return AbstractSlackCommand[]
     */
    public function resolveCommands($commands)
    {

        $commandMap = [];
        foreach ($commands as $key => $command) {
            $class = $this->resolve($command);
            $commandMap[$class->getCommandName()] = $class;
        }

        return $commandMap;
    }


    /**
     * @param $className
     * @return AbstractSlackCommand
     */
    public function resolve($className)
    {
        $class = new $className($this->payload);
        return $class;
    }


    /**
     * @param $text
     * @return AbstractSlackCommand
     * @throws \Exception
     */
    public function getCommand($text)
    {
        $name = explode(' ', $text);

        if (empty($name[0])) {
            throw new \Exception('You do not provide a command.');
        }

        if (!isset($this->commandMap[$name[0]])) {
            throw new \Exception('Command: ' . $name[0] . ' is not registered on server.');
        }
        $class = $this->commandMap[$name[0]];

        return $class;
    }
}