<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use Telegram;
use Storage;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\RequestException as GuzzleHttpRequestException;

class DoorStatusCommand extends Command
{
   /**
     * @var string Command Name
     */
    protected $name = "status";

    /**
     * @var string Command Description
     */
    protected $description = "現在 MOLi 是開的還是關的呢？";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        //get text use $update->all()['message']['text']
        $update = Telegram::getWebhookUpdates();

        $firebase = new \Firebase\FirebaseLib(env('FIREBASE'));
        $status = $firebase->get('/status');

        if ($status === '1') {
            $reply = 'MOLi 現在 關門中';
        } else if ($status === '0') {
            $reply = 'MOLi 現在 開門中';
        } else {
            $reply = '壞了，猴子們正在努力解決問題！';
        }

        $this->replyWithChatAction(['action' => Actions::TYPING]);
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        $this->replyWithMessage(['text' => $reply]);
        
        if ( $update->all()['message']['chat']['type'] == 'private' ) {

            $client = new GuzzleHttpClient([
                'headers' => [
                    'User-Agent' => 'MOLi Bot'
                ]
            ]);

            try {
                $move = $client->request('GET', env('SCREEN_TABLE'))->getBody();
                sleep(2);
                $response = $client->request('GET', env('SCREEN_SHOT'));
            } catch (GuzzleHttpRequestException $e) {
                return (new \Illuminate\Http\Response)->setStatusCode(200, 'OK');
            }

            $type = explode("/",$response->getHeader('Content-Type')[0]);

            if ($type[0] == 'image') {
                $fileName = rand(11111,99999);

                storage::disk('local')->put($fileName.'.'.$type[1], $response->getBody());

                $send = Telegram::sendPhoto([
                    'chat_id' => $update->all()['message']['chat']['id'],
                    'photo' => '../storage/app/'.$fileName.'.'.$type[1],
                ]);

                Storage::disk('local')->delete($fileName.'.'.$type[1]);
            }

            return (new \Illuminate\Http\Response)->setStatusCode(200, 'OK');
        }
    } 
}
