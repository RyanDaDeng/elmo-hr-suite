<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:16 PM
 */

namespace App\ELMOHRSuite\AwardApp\Commands\Collection;


use App\ELMOHRSuite\Core\Commands\AbstractSlackCommand;

class CookiesYouReceivedCommand extends AbstractSlackCommand
{

    /**
     * @var string $name
     */
    protected $commandName = 'sent';

    /**
     * @var string $description
     */
    protected $description = 'Check how many cookies your have received.';


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
        return 'OMG, you have so many cookies received that I cannot count.';
    }
}