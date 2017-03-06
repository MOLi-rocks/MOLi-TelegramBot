<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use Telegram;
use Storage;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;

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
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('GET', 'https://bot.moli.rocks:8000', ['timeout' => 10]);
        } catch (GuzzleHttpTransferException $e) {
            $this->replyWithChatAction(['action' => Actions::TYPING]);
            $this->replyWithMessage(['text' => '服務未啟動']);
            return (new \Illuminate\Http\Response)->setStatusCode(200, 'OK');
        }


        if (isset(json_decode($response->getBody())->{'Status'})) {
            $status = json_decode($response->getBody())->{'Status'};
        } else {
            $status = -3;// 隨便設定一個非 0 或 1 的值就當作壞掉了
        }

        //get text use $update->all()['message']['text']
        $update = Telegram::getWebhookUpdates();

        switch ($status){
            case "1":
                $reply = 'MOLi 現在 關門中';
            break;

            case "0":
                $reply = 'MOLi 現在 開門中';
            break;

            default:
                $reply = '門鎖狀態不明，猴子們正努力維修中！';
        }

        $this->replyWithChatAction(['action' => Actions::TYPING]);
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        $send = $this->replyWithMessage(['text' => $reply]);
        
        if ( $update->all()['message']['chat']['type'] == 'private' ) {
            $client = new GuzzleHttpClient([
                'headers' => [
                    'User-Agent' => 'MOLi Bot'
                ]
            ]);

            try {
                $response = $client->request('GET', env('SCREEN_SHOT'), ['timeout' => 10]);
            } catch (GuzzleHttpTransferException $e) {
                $this->replyWithMessage(['text' => '暫時無法取得截圖！']);
                return (new \Illuminate\Http\Response)->setStatusCode(200, 'OK');
            }

            $type = explode("/",$response->getHeader('Content-Type')[0]);

            if ($type[0] == 'image') {
                $fileName = rand(11111,99999);

                storage::disk('local')->put($fileName.'.'.$type[1], $response->getBody());

                $send = Telegram::sendPhoto([
                    'chat_id' => $update->all()['message']['chat']['id'],
                    'reply_to_message_id' => $send->getMessageId(),
                    'photo' => '../storage/app/'.$fileName.'.'.$type[1],
                ]);

                Storage::disk('local')->delete($fileName.'.'.$type[1]);
            }

            return (new \Illuminate\Http\Response)->setStatusCode(200, 'OK');
        }
    } 
}
