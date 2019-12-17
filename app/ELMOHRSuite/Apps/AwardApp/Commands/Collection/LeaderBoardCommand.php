<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:16 PM
 */

namespace App\ELMOHRSuite\Apps\AwardApp\Commands\Collection;

use App\ELMOHRSuite\Core\Commands\AbstractSlackCommand;

class LeaderBoardCommand extends AbstractSlackCommand
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
        return 'You are not allowed to see the result.';
    }
}