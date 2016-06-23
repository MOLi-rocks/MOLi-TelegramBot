<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class HelpCommand extends Command
{
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
        $commands = $this->telegram->getCommands();

        $text = '';

        foreach ($commands as $name => $handler) {
            if ($name != 'start')
                $text .= sprintf('/%s - %s'.PHP_EOL, $name, $handler->getDescription());
        }

        $text .= sprintf('Hints: ' . PHP_EOL);
        $text .= sprintf('1. 加入 MOLi 廣播頻道( https://telegram.me/MOLi_Channel )以獲得即時開關門資訊' . PHP_EOL);
        $text .= sprintf('2. 加入"非官方"暨大最新公告( https://telegram.me/ncnu_news )以快速獲得校內最新公告資訊' . PHP_EOL);

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->replyWithMessage(compact('text'));
    }

}
