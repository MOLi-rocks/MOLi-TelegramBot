<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;

use MOLiBot\Http\Requests;
use MOLiBot\Http\Controllers\Controller;

use Telegram;
use Storage;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\RequestException as GuzzleHttpRequestException;
use MOLiBot\WhoUseWhatCommand;

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
            'chat_id' => $request->input('chat_id', ''),
            'text' => $request->input('text', ''),
            'disable_notification' => $request->input('disable_notification', false)
        ]);
    }

    public function postSendPhoto(Request $request)
    {
        $fileName = 'BotAPI'.rand(11111,99999);

        $extension = '';

        $imgpath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

        if ( $request->hasFile('photo') ) {
            $extension = $request->photo->extension();

            $path = $request->photo->storeAs('/', $fileName.'.'.$extension);
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

            $type = explode('/', $response->getHeader('Content-Type')[0]);

            $extension = $type[1];

            if ($type[0] == 'image') {
                Storage::disk('local')->put($fileName.'.'.$extension, $response->getBody());
            } else {
                return response()->json(['massages' => 'Can\'t Get Photo From Url'], 404);
            }
        }

        $send = Telegram::sendPhoto([
            'chat_id' => $request->input('chat_id', ''),
            'photo' => $imgpath.$fileName.'.'.$extension,
            'disable_notification' => $request->input('disable_notification', false)
            //'caption' => 'Some caption'
        ]);

        Storage::disk('local')->delete($fileName.'.'.$extension);

        return $send;
    }

    public function postSendLocation(Request $request)
    {
        return $send = Telegram::sendLocation([
            'chat_id' => $request->input('chat_id', ''),
            'latitude' => $request->input('latitude', ''),
            'longitude' => $request->input('longitude', ''),
            'disable_notification' => $request->input('disable_notification', false)
        ]);
    }

    public function postWebhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);
        // Commands handler method returns an Update object.
        // So you can further process $update object
        // to however you want.
        Log::info($update);

        /*
        {
            "update_id":(number),
            "message":{
                "message_id":(number),
                "from":{
                    "id":(number),
                    "first_name":"",
                    "username":""
                },
                "chat":{
                    "id":(number),
                    "title":"",
                    "username":"",
                    "type":""
                },
                "date":(number),
                "new_chat_participant":{
                    "id":(number),
                    "first_name":"",
                    "username":""
                },
                "new_chat_member":{
                    "id":(number),
                    "first_name":"",
                    "username":""
                },
                "new_chat_members":[
                {
                    "id":(number),
                    "first_name":"",
                    "username":""
                },
                {
                    "id":(number),
                    "first_name":"",
                    "username":""
                }
                ]
            }
        }
        */
        if ( isset($update->all()['message']['new_chat_members']) && $update->all()['message']['chat']['id'] == -1001029969071 ) { //-1001029969071 = MOLi group
            Telegram::sendMessage([
                'chat_id' => $update->all()['message']['chat']['id'],
                'reply_to_message_id' => $update->all()['message']['message_id'],
                'text' => 
                    '歡迎來到 MOLi（創新自造者開放實驗室），這裡是讓大家一起創造、分享、實踐的開放空間。' . PHP_EOL . 
                    PHP_EOL .
                    '以下是一些資訊連結：' . PHP_EOL . 
                    PHP_EOL .
                    '/* MOLi 相關 */' . PHP_EOL .
                    '- MOLi 聊天群 @MOLi_Rocks' . PHP_EOL .
                    '- MOLi Bot @MOLiRocks_bot' . PHP_EOL .
                    '- MOLi 廣播頻道 @MOLi_Channel' . PHP_EOL .
                    '- MOLi 天氣廣播台 @MOLi_Weather'  . PHP_EOL .
                    '- MOLi 知識中心 http://hackfoldr.org/MOLi/' . PHP_EOL .
                    '- MOLi 首頁 https://MOLi.Rocks' . PHP_EOL .
                    '- MOLi Blog https://blog.moli.rocks' . PHP_EOL . 
                    PHP_EOL .
                    '/* NCNU 相關 */' . PHP_EOL .
                    '- 暨大最新公告 @NCNU_NEWS'  . PHP_EOL .
                    PHP_EOL .
                    '/* Telegram 相關 */' . PHP_EOL .
                    '- Telegram 非官方中文站 https://telegram.how'
            ]);
        } else if ($update->all()['message']['chat']['type'] == 'private') {
            //app('MOLiBot\Commands\HydraDVRRemoteControlCommand')->handle($update->all()['message']['text']);
            $commands = Telegram::getCommands();
            foreach ($commands as $name => $handler) {
                Log::info($name .'=>'. $handler);
            }
        }

        return response('Controller OK', 200);
    }
}
