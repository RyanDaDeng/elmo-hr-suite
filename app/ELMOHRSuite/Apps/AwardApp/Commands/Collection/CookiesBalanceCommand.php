<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:16 PM
 */

namespace App\ELMOHRSuite\Apps\AwardApp\Commands\Collection;

use App\ELMOHRSuite\Core\Commands\AbstractSlackCommand;

class CookiesBalanceCommand extends AbstractSlackCommand
{

    /**
     * @var string $name
     */
    protected $commandName = 'balance';

    /**
     * @var string $description
     */
    protected $description = 'Check your current cookies balance';

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
        return 'OMG, you are so rich, you have infinite cookies.';
    }
}