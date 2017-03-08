<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;

use MOLiBot\Http\Requests;
use MOLiBot\Http\Controllers\Controller;

use Telegram;
use Validator;
use Storage;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\RequestException as GuzzleHttpRequestException;

use Log;

class TelegramController extends Controller
{

    protected $telegram;

    public function __construct( Telegram $telegram )
    {
        $this->telegram = $telegram;
    }

    public function postSendMessage(Request $request)
    {
        return $send = Telegram::sendMessage([
            'chat_id' => $request['chat_id'],
            'text' => $request['text'],
            'disable_notification' => $request->input('disable_notification')
        ]);
    }

    public function postSendPhoto(Request $request)
    {
        $fileName = rand(11111,99999);

        if ( $request->hasFile('photo') ) {
            $extension = $request['photo']->getClientOriginalExtension();

            storage::disk('local')->put($fileName.'.'.$extension, file_get_contents($request->file('photo')->getRealPath()));

            $send = Telegram::sendPhoto([
                'chat_id' => $request['chat_id'],
                'photo' => '../storage/app/'.$fileName.'.'.$extension,
                'disable_notification' => $request->input('disable_notification')
                //'caption' => 'Some caption'
            ]);

            Storage::disk('local')->delete($fileName.'.'.$extension);

            return $send;
        }

        if ( $request->input('photo') ) {
            //收到網址的話先把圖抓下來，因為有些 host 沒有 User-Agent 這個 header 的話會沒辦法用
            //Ex: hydra DVR
            $client = new GuzzleHttpClient([
                'headers' => [
                    'User-Agent' => 'MOLi Bot'
                ]
            ]);

            try {
                $response = $client->request('GET', $request['photo']);
            } catch (GuzzleHttpRequestException $e) {
                return response()->json(['massages' => 'Can\'t Get Photo From Url'], 404);
            }

            $type = explode("/",$response->getHeader('Content-Type')[0]);

            if ($type[0] == 'image') {
                storage::disk('local')->put($fileName.'.'.$type[1], $response->getBody());

                $send = Telegram::sendPhoto([
                    'chat_id' => $request['chat_id'],
                    'photo' => '../storage/app/'.$fileName.'.'.$type[1],
                    'disable_notification' => $request->input('disable_notification')
                    //'caption' => 'Some caption'
                ]);

                Storage::disk('local')->delete($fileName.'.'.$type[1]);

                return $send;
            }
        }

        return $send = Telegram::sendPhoto([
            'chat_id' => $request->input('chat_id', ''),
            'photo' => $request->input('photo', ''),
            'disable_notification' => $request->input('disable_notification')
            //'caption' => 'Some caption'
        ]);
    }

    public function postSendLocation(Request $request)
    {
        return $send = Telegram::sendLocation([
            'chat_id' => $request['chat_id'],
            'latitude' => $request['latitude'],
            'longitude' => $request['longitude'],
            'disable_notification' => $request->input('disable_notification')
        ]);
    }

    public function postWebhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);
        // Commands handler method returns an Update object.
        // So you can further process $update object
        // to however you want.
        Log::info($update);
    }
}
