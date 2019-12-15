<?php

namespace App\Http\Controllers\LeaveApp;

use App\ELMOHRSuite\LeaveApp\GroupCommands\LeaveGroupCommand;
use App\ELMOHRSuite\LeaveApp\InteractiveActions\LeaveInteractiveManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    /**
     * @param Request $request
     * @return array
     */
    public function test(Request $request)
    {

        return [
            'test' => 'hello'
        ];
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
