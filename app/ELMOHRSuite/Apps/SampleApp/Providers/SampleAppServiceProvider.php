<?php


namespace App\ELMOHRSuite\Apps\SampleApp\Providers;


use App\ELMOHRSuite\Core\Providers\AbstractServiceProvider;

class SampleAppServiceProvider extends AbstractServiceProvider
{
    public function getSlackRoute()
    {
        return __DIR__ . '/../Routes/slack.php';
    }
}