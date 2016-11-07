<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use MOLiBot\Commands\HelpList;

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
        $text = $this->helptext();

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->replyWithMessage(compact('text'));

    }

}
