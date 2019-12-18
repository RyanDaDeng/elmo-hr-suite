<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/7/31
 * Time: 7:34 PM
 */

namespace App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\BlockActions;

use App\ELMOHRSuite\Core\InteractiveManager\AbstractInteractive;
use Illuminate\Support\Facades\Response;

class LeaveApprovedAction extends AbstractInteractive
{
    /**
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle()
    {
        // todo

        return Response::json([], 200);
    }
}