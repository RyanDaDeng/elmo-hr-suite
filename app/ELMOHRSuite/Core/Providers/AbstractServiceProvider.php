<?php


namespace App\ELMOHRSuite\Core\Providers;


use Illuminate\Support\ServiceProvider;

abstract class AbstractServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom($this->getSlackRoute());
    }

    abstract public function getSlackRoute();
}