<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class WhoamiCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'whoami';

    /**
     * @var string Command Description
     */
    protected $description = '取得 Telegram 使用者詳細資料';

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $msg = $this->getUpdate()->getMessage();

        $userid = $msg->getFrom()->getId();

        $username = $msg->getFrom()->getUsername();

        $first_name = $msg->getFrom()->getFirstName();

        $chatid = $msg->getChat()->getId();

        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $this->replyWithMessage([
            'text' => '您是 ' . $first_name . PHP_EOL .
                '您所設定的 username 為 @' . $username . PHP_EOL .
                '您的 Telegram user ID 為 ' . $userid . PHP_EOL .
                '目前所在的頻道 ID 為 ' . $chatid,
            'reply_to_message_id' => $msg->getMessageId()
        ]);

        return response('OK', 200);
    }
}
