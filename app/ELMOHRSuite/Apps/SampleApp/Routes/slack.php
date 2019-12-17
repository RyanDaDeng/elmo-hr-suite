<?php

/*
|--------------------------------------------------------------------------
| Slack Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Slack routes for your slack application. These
| routes are loaded by the SlackAppServiceProvider.
|
*/

use Illuminate\Support\Facades\Route;

Route::prefix('apps/sample')
    ->namespace('\App\ELMOHRSuite\Apps\SampleApp\Controllers')
    ->group(function () {
        Route::get('hello', 'SlackController@hello');
        Route::post('command', 'SlackController@command');
        Route::post('interactive', 'SlackController@interactive');
    });