<?php


namespace App\ELMOHRSuite\Apps\AwardApp\Controllers;

use App\ELMOHRSuite\Apps\AwardApp\Commands\AwardsGroupCommand;
use App\ELMOHRSuite\Apps\AwardApp\Commands\Collection\OpenAwardCommand;
use App\ELMOHRSuite\Apps\AwardApp\Events\AwardsEventManager;
use App\ELMOHRSuite\Apps\AwardApp\InteractiveActions\AwardInteractiveManager;
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
        return (new OpenAwardCommand($request->all()))->execute();
    }

    public function interactive(Request $request)
    {
        return (new AwardInteractiveManager(json_decode($request->all()['payload'], true)))->process();
    }
}