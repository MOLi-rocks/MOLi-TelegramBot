<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use DB;
use Telegram;
use Storage;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;

use MOLiBot\WhoUseWhatCommand;

class HydraDVRRemoteControlCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'DVRremoteController';

    /**
     * @var string Command Description
     */
    protected $description = 'MOLi DVR 遙控器';

    /**
     * @var WhoUseWhatCommand
     */
    private $WhoUseWhatCommandModel;

    /**
     * Create a new command instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->WhoUseWhatCommandModel = WhoUseWhatCommand::class;
    }
    
    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $update = Telegram::getWebhookUpdates();

        if ( $update->all()['message']['chat']['type'] == 'private' ) {
            if (empty($arguments)) {
                $keyboard = [
                    ['Up'],
                    ['Left', 'Right'],
                    ['Down'],
                    ['Zoom In', 'ESC', 'Zoom Out']
                ];

                $reply_markup = Telegram::replyKeyboardMarkup([
                    'keyboard' => $keyboard,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => false
                ]);

                Telegram::sendMessage([
                    'chat_id' => $update->all()['message']['chat']['id'],
                    'text' => '歡迎使用遙控器 XD',
                    'reply_to_message_id' => $update->all()['message']['message_id'],
                    'reply_markup' => $reply_markup
                ]);

                DB::transaction(function () use ($update) {
                    $this->WhoUseWhatCommandModel->where('user-id', '=', $update->all()['message']['from']['id'])->delete();

                    $this->WhoUseWhatCommandModel->create([
                        'user-id' => $update->all()['message']['from']['id'],
                        'command' => $this->name
                    ]);
                });

                return response('OK', 200); // 強制結束 command
            }

            /*
            if (WhoUseWhatCommand::where('command', '=', $this->name)
                ->where('user-id', '!=', $update->all()['message']['from']['id'])
                ->exists()) {
                Telegram::sendMessage([
                    'chat_id' => $update->all()['message']['chat']['id'],
                    'text' => '有其他人正在使用遙控器，請稍後再試（？',
                    'reply_to_message_id' => $update->all()['message']['message_id']
                ]);

                return response('OK', 200); // 強制結束 command
            }
            */

            switch ($arguments) {
                case 'ESC':
                    $reply_markup = Telegram::replyKeyboardHide();

                    Telegram::sendMessage([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'text' => '感謝使用遙控器 XD',
                        'reply_markup' => $reply_markup
                    ]);

                    $this->control('door', $update);

                    $this->WhoUseWhatCommandModel->where('user-id', '=', $update->all()['message']['from']['id'])->delete();

                    break;

                case 'Up':
                    $this->control('up', $update);
                    break;

                case 'Down':
                    $this->control('down', $update);
                    break;

                case 'Left':
                    $this->control('left', $update);
                    break;

                case 'Right':
                    $this->control('right', $update);
                    break;

                case 'Zoom In':
                    $this->control('zoomin', $update);
                    break;

                case 'Zoom Out':
                    $pic = $this->control('zoomout', $update);
                    break;

                default:
                    Telegram::sendMessage([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'text' => '不懂 QQ',
                        'reply_to_message_id' => $update->all()['message']['message_id']
                    ]);

                    break;
            }
        } else {
            $this->replyWithChatAction(['action' => Actions::TYPING]);

            Telegram::sendMessage([
                'chat_id' => $update->all()['message']['chat']['id'],
                'text' => '此功能限一對一對話',
                'reply_to_message_id' => $update->all()['message']['message_id']
            ]);

            return response('OK', 200); // 強制結束 command
        }

        return response('OK', 200);
    }

    private function control($position, $update) {
        $client = new GuzzleHttpClient();

        try {
            $client->request('GET', env('DVR_BASE_URL').$position, [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'Accept'     => 'application/json'
                ],
                'timeout' => 10
            ]);

            if ($position != 'door') {
                $response = $client->request('GET', env('DVR_SHOT'), [
                    'headers' => [
                        'User-Agent' => 'MOLi Bot',
                        'Accept' => 'application/json'
                    ],
                    'timeout' => 10
                ]);

                $type = explode("/", $response->getHeader('Content-Type')[0]);

                $imgpath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

                if ($type[0] == 'image') {
                    $fileName = 'HydraDVRRemoteController' . rand(11111, 99999);

                    Storage::disk('local')->put($fileName . '.' . $type[1], $response->getBody());

                    Telegram::sendPhoto([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'reply_to_message_id' => $update->all()['message']['message_id'],
                        'photo' => $imgpath . $fileName . '.' . $type[1],
                    ]);

                    Storage::disk('local')->delete($fileName . '.' . $type[1]);
                }
            }

            return 'done';
        } catch (GuzzleHttpTransferException $e) {
            $this->replyWithChatAction(['action' => Actions::TYPING]);

            $this->replyWithMessage(['text' => '網路連線異常 QAQ']);

            return 'QQ';
        }
    }
}
