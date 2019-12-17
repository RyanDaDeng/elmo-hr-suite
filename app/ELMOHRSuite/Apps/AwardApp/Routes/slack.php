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

Route::prefix('apps/awards')
    ->namespace('\App\ELMOHRSuite\Apps\AwardApp\Controllers')
    ->group(function () {
        Route::get('hello', 'SlackController@hello')->name('awards.hello');
        Route::post('event', 'SlackController@event')->name('awards.event');
        Route::post('command', 'SlackController@command')->name('awards.command');
        Route::post('interactive', 'SlackController@interactive')->name('awards.interactive');
    });