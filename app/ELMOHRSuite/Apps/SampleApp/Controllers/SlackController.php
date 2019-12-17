<?php


namespace App\ELMOHRSuite\Apps\SampleApp\Controllers;

use App\ELMOHRSuite\Apps\SampleApp\Commands\SampleGroupCommand;
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

        return (new SampleGroupCommand($request->all()))->execute();
    }
}