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
            return response('OK', 200); // 強制結束 command
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
                return response('OK', 200);// 強制結束 command
            }
           
            $send = Telegram::sendPhoto([
                'chat_id' => $update->all()['message']['chat']['id'],
                'reply_to_message_id' => $send->getMessageId(),
                'photo' => env('SCREEN_SHOT'),
            ]);
        }

        return response('OK', 200);
    } 
}
