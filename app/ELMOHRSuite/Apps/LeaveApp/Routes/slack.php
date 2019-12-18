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

Route::prefix('apps/leave')
    ->namespace('\App\ELMOHRSuite\Apps\LeaveApp\Controllers')
    ->group(function () {
        Route::get('hello', 'SlackController@hello')->name('leave.hello');
        Route::post('command', 'SlackController@command')->name('leave.command');
        Route::post('interactive', 'SlackController@interactive')->name('leave.interactive');
        Route::post('calendar-insert', 'SlackController@calendarInsert')->name('leave.calendar-insert');
    });