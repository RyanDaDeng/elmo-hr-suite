<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:16 PM
 */

namespace App\ELMOHRSuite\AwardApp\Commands\Collection;

use App\ELMOHRSuite\Core\Commands\AbstractSlackCommand;

class CookiesYouSentCommand extends AbstractSlackCommand
{

    /**
     * @var string $name
     */
    protected $commandName = 'received';

    /**
     * @var string $description
     */
    protected $description = 'Check how many cookies your have sent.';

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
        return 'OMG, you have so many cookies sent that I cannot count.';
    }
}