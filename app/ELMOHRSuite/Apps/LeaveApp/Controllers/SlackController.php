<?php


namespace App\ELMOHRSuite\Apps\LeaveApp\Controllers;

use App\ELMOHRSuite\Apps\LeaveApp\Commands\LeaveGroupCommand;
use App\ELMOHRSuite\Apps\LeaveApp\InteractiveActions\LeaveInteractiveManager;
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
     * @return mixed|string
     */
    public function command(Request $request)
    {

        return (new LeaveGroupCommand($request->all()))->execute();
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