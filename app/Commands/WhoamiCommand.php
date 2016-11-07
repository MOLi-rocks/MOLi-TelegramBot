<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use Telegram;

class WhoamiCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "whoami";

    /**
     * @var string Command Description
     */
    protected $description = "取得 Telegram 使用者詳細資料";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $update = Telegram::getWebhookUpdates();

        $userid = $update->all()['message']['from']['id'];

        $username = $update->all()['message']['from']['username'];

        $first_name = $update->all()['message']['from']['first_name'];

        $chatid = $update->all()['message']['chat']['id'];

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->replyWithMessage([
            'text' => '您是 '.$first_name.PHP_EOL.
                '您所設定的 username 為 @'.$username.PHP_EOL.
                '您的 Telegram user ID 為 `'.$userid.'`'.PHP_EOL.
                '目前所在的頻道 ID 為 `'.$chatid.'`',
            'parse_mode' => 'Markdown'
        ]);
    }
}
