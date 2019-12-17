<?php


namespace App\ELMOHRSuite\Apps\LeaveApp\Providers;


use App\ELMOHRSuite\Core\Providers\AbstractServiceProvider;

class LeaveServiceProvider extends AbstractServiceProvider
{
    public function getSlackRoute()
    {
        return __DIR__ . '/../Routes/slack.php';
    }
}