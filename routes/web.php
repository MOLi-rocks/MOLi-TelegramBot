<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MOLiBotController@getIndex');

Route::get('/line-notify-auth', 'LINENotifyController@auth')->name('line_notify_auth');

Route::get('/line-notify-stats', 'LINENotifyController@stats')->name('line_notify_stats');

Route::any('/connect-test', 'MOLiBotController@connectTester');

Route::get('/ncnu-rss', 'MOLiBotController@getNCNU_RSS');

Route::get('/ncdr-rss', 'MOLiBotController@getNCDR_RSS');

Route::get('/ncnu-staff-contact/{keyword?}', 'MOLiBotController@getStaffContact');

Route::get('/fuel-price', 'MOLiBotController@getFuelPrice');

Route::get('/history-fuel-price', 'MOLiBotController@getHistoryFuelPrice');

Route::group(['middleware' => 'bot.token'], function () {
    Route::post('messages', 'TelegramController@postSendMessage');

    Route::post('photos', 'TelegramController@postSendPhoto');

    Route::post('locations', 'TelegramController@postSendLocation');
});

Route::post(config('telegram.bot_token'), 'TelegramController@postWebhook');

Route::post(config('ncdr.url'), 'MOLiBotController@postNCDR');

// keep this route at bottom of file
Route::any('/{any?}', 'MOLiBotController@anyRoute');