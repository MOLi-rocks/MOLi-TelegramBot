<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

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

                //WhoUseWhatCommand::create()

                return response('OK', 200); // 強制結束 command
            }

            $args = explode(' ', $arguments);

            $doAction = $args[0];

            switch ($doAction) {
                case 'ESC':
                    $reply_markup = Telegram::replyKeyboardHide();

                    Telegram::sendMessage([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'text' => '感謝使用遙控器 XD',
                        'reply_markup' => $reply_markup
                    ]);

                    break;

                case 'Up':
                    Telegram::sendMessage([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'text' => 'Move Camera Up!',
                        'reply_to_message_id' => $update->all()['message']['reply_to_message']['message_id']
                    ]);

                    break;

                case 'Down':
                    Telegram::sendMessage([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'text' => 'Move Camera Down!',
                        'reply_to_message_id' => $update->all()['message']['reply_to_message']['message_id']
                    ]);

                    break;

                case 'Left':
                    Telegram::sendMessage([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'text' => 'Move Camera Left!',
                        'reply_to_message_id' => $update->all()['message']['reply_to_message']['message_id']
                    ]);

                    break;

                case 'Right':
                    Telegram::sendMessage([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'text' => 'Move Camera Right!',
                        'reply_to_message_id' => $update->all()['message']['reply_to_message']['message_id']
                    ]);

                    break;

                case 'Zoom In':
                    Telegram::sendMessage([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'text' => 'Zoom Camera In!',
                        'reply_to_message_id' => $update->all()['message']['reply_to_message']['message_id']
                    ]);

                    break;

                case 'Zoom Out':
                    Telegram::sendMessage([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'text' => 'Zoom Camera Out!',
                        'reply_to_message_id' => $update->all()['message']['reply_to_message']['message_id']
                    ]);

                    break;

                default:
                    Telegram::sendMessage([
                        'chat_id' => $update->all()['message']['chat']['id'],
                        'text' => '不懂 QQ',
                        'reply_to_message_id' => $update->all()['message']['reply_to_message']['message_id']
                    ]);

                    break;
            }
            /*
            $client = new GuzzleHttpClient();

            try {
                $response = $client->request('GET', 'https://moli.kktix.cc/events.json', [
                    'headers' => [
                        'User-Agent' => 'MOLi Bot',
                        'Accept'     => 'application/json'
                    ],
                    'timeout' => 10
                ]);
            } catch (\GuzzleHttp\Exception\TransferException $e) {
                $this->replyWithChatAction(['action' => Actions::TYPING]);
                $this->replyWithMessage(['text' => '網路連線異常 QAQ']);
                return response('OK', 200); // 強制結束 command
            }

            $body = $response->getBody();
            $json = json_decode($body, true);
            $activity = 0;

            foreach ($json['entry'] as $num => $detail) {
                if ( strtotime($detail['published']) > strtotime('now') ) {
                    $this->replyWithChatAction(['action' => Actions::TYPING]);

                    $this->replyWithMessage([
                        'text' => $detail['title'] . PHP_EOL . '' . PHP_EOL . $detail['content'] . PHP_EOL . '' . PHP_EOL . $detail['url']
                    ]);

                    $activity++;
                } else break;
            }

            if ($activity == 0) {
                $this->replyWithChatAction(['action' => Actions::TYPING]);
                $this->replyWithMessage(['text' => '最近無排定活動，歡迎在群組挖坑' . PHP_EOL . 'https://www.facebook.com/groups/MOLi.rocks']);
            }

            return response('OK', 200);
            */
        } else {
            $this->replyWithChatAction(['action' => Actions::TYPING]);

            $this->replyWithMessage(['text' => '此功能限一對一對話', 'reply_to_message_id' => $update->all()['message']['message_id']]);

            return response('OK', 200); // 強制結束 command
        }

        return response('OK', 200);
    }
}
