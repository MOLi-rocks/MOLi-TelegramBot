<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use Telegram;

class SearchStaffContectCommand extends Command
{
    /**
     * @var string Command Name
     */

    protected $name = "staffcontect";

    /**
     * @var string Command Description
     */

    protected $description = "使用關鍵字搜尋暨大教職員聯絡資訊";

    /**
     * @inheritdoc
     */

    public function handle($arguments)
    {
        if (empty($arguments)) {
            $this->replyWithChatAction(['action' => Actions::TYPING]);

            $this->replyWithMessage(['text' => '請輸入一個關鍵字']);

            return (new \Illuminate\Http\Response)->setStatusCode(200, 'OK');
        }

        $args = explode(" ", $arguments);

        $keyword = $args[0];

        $json = app('MOLiBot\Http\Controllers\MOLiBotController')->getStaffContect($keyword);

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->replyWithMessage(['text' => $json]);
    }
}
