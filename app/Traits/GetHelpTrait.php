<?php

namespace MOLiBot\Traits;

use Telegram;

trait GetHelpTrait
{
    public function helptext()
    {
        //修改此檔案將同步修改 help 及 start 指令所顯示的內容
        $commands = Telegram::getCommands();

        $text = '';

        $hidden = ['start', 'whoami', 'DVRremoteController', 'ncdrstart', 'MagneticLockStatus', 'fuck'];//不想顯示在 help 及 start 的指令請填在這個陣列

        $commands = array_except($commands, $hidden);

        foreach ($commands as $name => $handler) {
            $text .= sprintf('/%s - %s'.PHP_EOL, $name, $handler->getDescription());
        }

        $text .= sprintf('Hints: ' . PHP_EOL);
        $text .= sprintf('1. 加入 MOLi 廣播頻道 ( @MOLi_Channel ) 以獲得即時開關門資訊' . PHP_EOL);
        $text .= sprintf('2. 加入"非官方"暨大最新公告 ( @NCNU_NEWS ) 以快速獲得校內最新公告資訊' . PHP_EOL);
        $text .= sprintf('3. MOLi 天氣廣播 ( @MOLi_Weather ) 已開台' . PHP_EOL);

        return $text;
    }
}