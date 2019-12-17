<?php


namespace App\ELMOHRSuite\AwardApp\Controllers;

use App\ELMOHRSuite\AwardApp\Commands\AwardsGroupCommand;
use App\ELMOHRSuite\AwardApp\Events\AwardsEventManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SlackController extends Controller
{

    /**
     * @return string
     */
    public function hello()
    {
        return 'Hello World';
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function event(Request $request)
    {
        return (new AwardsEventManager($request->all()))->execute();
    }

    /**
     * @param Request $request
     * @return mixed|string
     */
    public function command(Request $request)
    {

        return (new AwardsGroupCommand($request->all()))->execute();
    }


    /**
     * @param Request $request
     * @return LeaveInteractiveManager|array
     */
    public function interactive(Request $request)
    {

        return (new LeaveInteractiveManager(json_decode($request->all()['payload'], 1)))->process();
    }
}