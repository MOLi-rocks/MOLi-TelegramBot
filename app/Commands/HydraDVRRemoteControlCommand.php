<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use DB;
use Telegram;
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
                    WhoUseWhatCommand::where('user-id', '=', $update->all()['message']['from']['id'])->delete();

                    WhoUseWhatCommand::create([
                        'user-id' => $update->all()['message']['from']['id'],
                        'command' => $this->name
                    ]);
                });

                return response('OK', 200); // 強制結束 command
            }

            if (WhoUseWhatCommand::where('command', '=', $this->name)->exists()) {
                Telegram::sendMessage([
                    'chat_id' => $update->all()['message']['chat']['id'],
                    'text' => '有其他人正在使用遙控器，請稍後再試（？',
                    'reply_to_message_id' => $update->all()['message']['message_id']
                ]);

                return response('OK', 200); // 強制結束 command
            }

            switch ($arguments) {
                case 'ESC':
                    $reply_markup = Telegram::replyKeyboardHide();

                    Telegram::sendMessage([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'text' => '感謝使用遙控器 XD',
                        'reply_markup' => $reply_markup
                    ]);

                    $this->control('door');

                    WhoUseWhatCommand::where('user-id', '=', $update->all()['message']['from']['id'])->delete();

                    break;

                case 'Up':
                    $pic = $this->control('up');

                    if ($pic == env('DVR_SHOT')) {
                        Telegram::sendPhoto([
                            'chat_id' => $update->all()['message']['chat']['id'],
                            'photo' => env('DVR_SHOT'),
                            'reply_to_message_id' => $update->all()['message']['message_id'],
                            'disable_notification' => true
                        ]);
                    }

                    break;

                case 'Down':
                    $pic = $this->control('down');

                    if ($pic == env('DVR_SHOT')) {
                        Telegram::sendPhoto([
                            'chat_id' => $update->all()['message']['chat']['id'],
                            'photo' => env('DVR_SHOT'),
                            'reply_to_message_id' => $update->all()['message']['message_id'],
                            'disable_notification' => true
                        ]);
                    }

                    break;

                case 'Left':
                    $pic = $this->control('left');

                    if ($pic == env('DVR_SHOT')) {
                        Telegram::sendPhoto([
                            'chat_id' => $update->all()['message']['chat']['id'],
                            'photo' => env('DVR_SHOT'),
                            'reply_to_message_id' => $update->all()['message']['message_id'],
                            'disable_notification' => true
                        ]);
                    }

                    break;

                case 'Right':
                    $pic = $this->control('right');

                    if ($pic == env('DVR_SHOT')) {
                        Telegram::sendPhoto([
                            'chat_id' => $update->all()['message']['chat']['id'],
                            'photo' => env('DVR_SHOT'),
                            'reply_to_message_id' => $update->all()['message']['message_id'],
                            'disable_notification' => true
                        ]);
                    }

                    break;

                /*
                case 'Zoom In':
                    $pic = $this->control('zoomin');

                    if ($pic == env('DVR_SHOT')) {
                        Telegram::sendPhoto([
                            'chat_id' => $update->all()['message']['chat']['id'],
                            'photo' => env('DVR_SHOT'),
                            'reply_to_message_id' => $update->all()['message']['message_id'],
                            'disable_notification' => true
                        ]);
                    }

                    break;

                case 'Zoom Out':
                    $pic = $this->control('zoomout');

                    if ($pic == env('DVR_SHOT')) {
                        Telegram::sendPhoto([
                            'chat_id' => $update->all()['message']['chat']['id'],
                            'photo' => env('DVR_SHOT'),
                            'reply_to_message_id' => $update->all()['message']['message_id'],
                            'disable_notification' => true
                        ]);
                    }

                    break;
                */

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

    private function control($position) {
        $client = new GuzzleHttpClient();

        try {
            $client->request('GET', env('DVR_BASE_URL').$position, [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'Accept'     => 'application/json'
                ],
                'timeout' => 10
            ]);

            return env('DVR_SHOT');
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            $this->replyWithChatAction(['action' => Actions::TYPING]);

            $this->replyWithMessage(['text' => '網路連線異常 QAQ']);

            return 'QQ';
        }
    }
}
