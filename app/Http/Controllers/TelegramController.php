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
            return $send = Telegram::sendPhoto([
                'chat_id' => $request['chat_id'],
                'photo' => $request['photo']
                //'caption' => 'Some caption'
            ]);
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
