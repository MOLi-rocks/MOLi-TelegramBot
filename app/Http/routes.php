<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'MOLiBotController@getIndex');

Route::get('/ncnu-rss', 'MOLiBotController@getNCNU_RSS');

Route::get('/ncnu-staff-contact/{keyword?}', 'MOLiBotController@getStaffContact');

Route::get('/fuel-price', 'MOLiBotController@getFuelPrice');

Route::get('/history-fuel-price', 'MOLiBotController@getHistoryFuelPrice');

Route::group(['middleware' => 'bot.token'], function () {
    Route::post('messages', 'TelegramController@postSendMessage');

    Route::post('photos', 'TelegramController@postSendPhoto');

    Route::post('locations', 'TelegramController@postSendLocation');
});

Route::post(env('TELEGRAM_BOT_TOKEN'), 'TelegramController@postWebhook');

Route::post(env('NCDR_URL'), 'MOLiBotController@postNCDR');
