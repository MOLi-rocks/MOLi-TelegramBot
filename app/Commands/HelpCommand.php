<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

trait HelpList
{
    public function helptext()
    {
        //修改此檔案將同步修改 help 及 start 指令所顯示的內容
        $commands = $this->telegram->getCommands();

        $text = '';

        $hidden = ['start', 'whoami', 'staffcontect'];//不想顯示在 help 及 start 的指令請填在這個陣列

        $commands = array_except($commands, $hidden);

        foreach ($commands as $name => $handler) {
            $text .= sprintf('/%s - %s'.PHP_EOL, $name, $handler->getDescription());
        }

        $text .= sprintf('Hints: ' . PHP_EOL);
        $text .= sprintf('1. 加入 MOLi 廣播頻道( https://telegram.me/MOLi_Channel )以獲得即時開關門資訊' . PHP_EOL);
        $text .= sprintf('2. 加入"非官方"暨大最新公告( https://telegram.me/ncnu_news )以快速獲得校內最新公告資訊' . PHP_EOL);

        return $text;
    }

}

class HelpCommand extends Command
{
    use HelpList;
    /**
     * @var string Command Name
     */
    protected $name = 'help';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = '列出可用指令';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $text = $this->helptext();//call trait HelpList at app/Commands/HelpCommand.php

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->replyWithMessage(compact('text'));

    }

}
