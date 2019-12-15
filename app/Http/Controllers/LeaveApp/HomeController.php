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

    public function test(Request $request)
    {

        return [
            'test' => 'hello'
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function command(Request $request)
    {

        return (new LeaveGroupCommand($request->all()))->execute();
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function interactive(Request $request)
    {

        return (new LeaveInteractiveManager(json_decode($request->all()['payload'], 1)))->process();
    }
}
