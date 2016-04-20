<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;

use MOLiBot\Http\Requests;
use MOLiBot\Http\Controllers\Controller;

use Telegram;
use Validator;
use Storage;

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
            'text' => $request['text']
        ]);
    }

    public function postSendPhoto(Request $request)
    {
        if (!$request->has('photo') || !$request->has('chat_id')) {
            return $send = Telegram::sendPhoto([
                'chat_id' => $request['chat_id'],
                'photo' => $request['photo']
                //'caption' => 'Some caption'
            ]);
        }

        if ($request->hasFile('photo')) {
            $fileName = rand(11111,99999);
            $extension = $request['photo']->getClientOriginalExtension();

            storage::disk('local')->put($fileName.'.'.$extension, file_get_contents($request->file('photo')->getRealPath()));

            $send = Telegram::sendPhoto([
                'chat_id' => $request['chat_id'],
                'photo' => '../storage/app/'.$fileName.'.'.$extension
                //'caption' => 'Some caption'
            ]);

            Storage::disk('local')->delete($fileName.'.'.$extension);

            return $send;
        } else {
            $client = new \GuzzleHttp\Client([
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36)'
                ]
            ]);

            $response = $client->request('GET', $request['photo']);

            $fileName = rand(11111,99999);

            $type = explode("/",$response->getHeader('Content-Type')[0]);

            if ($type[0] == 'image') {
                storage::disk('local')->put($fileName.'.'.$type[1], $response->getBody());

                $send = Telegram::sendPhoto([
                    'chat_id' => $request['chat_id'],
                    'photo' => '../storage/app/'.$fileName.'.'.$type[1]
                    //'caption' => 'Some caption'
                ]);

                Storage::disk('local')->delete($fileName.'.'.$type[1]);

                return $send;
            } else {
                return $send = Telegram::sendPhoto([
                'chat_id' => $request['chat_id'],
                'photo' => $request['photo']
                //'caption' => 'Some caption'
                ]);
            }
        }
    }

    public function postSendLocation(Request $request)
    {
        return $send = Telegram::sendLocation([
            'chat_id' => $request['chat_id'],
            'latitude' => $request['latitude'],
            'longitude' => $request['longitude']
        ]);
    }

    public function postWebhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);
        // Commands handler method returns an Update object.
        // So you can further process $update object
        // to however you want.
    }
}
