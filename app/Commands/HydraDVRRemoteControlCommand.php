<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use Telegram;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;

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
                    ['Zoom In', 'text' => '123ESC', 'Zoom Out']
                ];

                $reply_markup = Telegram::replyKeyboardMarkup([
                    'keyboard' => $keyboard,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => false
                ]);

                $this->replyWithMessage(['text' => '歡迎使用遙控器 XD', 'reply_markup' => $reply_markup]);

                return response('OK', 200); // 強制結束 command
            }

            $args = explode(' ', $arguments);

            $doAction = $args[0];

            switch ($doAction) {
                case 'ESC':
                    $reply_markup = Telegram::replyKeyboardHide();
                    $this->replyWithMessage(['text' => '感謝使用遙控器 XD', 'reply_markup' => $reply_markup]);
                    break;

                case 'Up':
                    $this->replyWithMessage(['text' => 'Move Camera Up!']);
                    break;

                case 'Down':
                    $this->replyWithMessage(['text' => 'Move Camera Down!']);
                    break;

                case 'Left':
                    $this->replyWithMessage(['text' => 'Move Camera Left!']);
                    break;

                case 'Right':
                    $this->replyWithMessage(['text' => 'Move Camera Right!']);
                    break;

                case 'Zoom In':
                    $this->replyWithMessage(['text' => 'Zoom Camera In!']);
                    break;

                case 'Zoom Out':
                    $this->replyWithMessage(['text' => 'Zoom Camera Out!']);
                    break;

                default:
                    $this->replyWithMessage(['text' => '不懂 QQ']);
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
