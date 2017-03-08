<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use Telegram;

class SearchStaffContactCommand extends Command
{
    /**
     * @var string Command Name
     */

    protected $name = "staffcontact";

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

            $this->replyWithMessage(['text' => '請直接在指令後方加上關鍵字以便查詢']);

            return response('OK', 200); // 強制結束 command
        }

        $args = explode(" ", $arguments);

        $keyword = $args[0];

        $json = app('MOLiBot\Http\Controllers\MOLiBotController')->getStaffContact($keyword);

        $text = '';

        $count = 0;

        if (count($json) > 12) {
            $text .= '結果超過 10 筆，建議使用更精確關鍵字搜尋或至 http://ccweb1.ncnu.edu.tw/telquery/StaffQuery.asp 直接搜尋' . PHP_EOL . PHP_EOL . PHP_EOL;
        }

        foreach ($json as $index => $items) {
            if ($index !== 0 && $count < 10) {
                $text .= $json[0][0] . ': ' . $json[$index][0] . PHP_EOL .
                         $json[0][2] . ': ' . $json[$index][2] . PHP_EOL .
                         $json[0][4] . ': ' . $json[$index][4] . PHP_EOL .
                         $json[0][6] . ': ' . $json[$index][6] . PHP_EOL .
                         $json[0][7] . ': ' . $json[$index][7] . PHP_EOL . PHP_EOL . PHP_EOL;
                $count++;
            }
        }

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->replyWithMessage(['text' => $text]);
    }
}
