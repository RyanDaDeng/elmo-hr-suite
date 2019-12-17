<?php


namespace App\ELMOHRSuite\Apps\AwardApp\Providers;


use App\ELMOHRSuite\Core\Providers\AbstractServiceProvider;

class AwardsServiceProvider extends AbstractServiceProvider
{
    public function getSlackRoute()
    {
        return __DIR__ . '/../Routes/slack.php';
    }
}