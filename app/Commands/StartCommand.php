<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "MOLi Bot 歡迎訊息";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        $this->replyWithMessage(['text' => 'Hello! 歡迎使用 MOLi Bot，以下是目前可用的功能：']);

        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $text = app('MOLiBot\Commands\HelpCommand')->helptext();;//call function in app/Commands/HelpCommand.php

        // This will prepare a list of available commands and send the user.
        // First, Get an array of all registered commands
        // They'll be in 'command-name' => 'Command Handler Class' format.
        //$commands = $this->getTelegram()->getCommands();

        // Build the list
        //$response = '';
        //foreach ($commands as $name => $command) {
        //    if ($name != 'start')
        //        $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        //}

        //$response .= sprintf('Hints: ' . PHP_EOL);
        //$response .= sprintf('1. 加入 MOLi 廣播頻道( https://telegram.me/MOLi_Channel )以獲得即時開關門資訊' . PHP_EOL);
        //$response .= sprintf('2. 加入"非官方"暨大最新公告( https://telegram.me/ncnu_news )以快速獲得校內最新公告資訊' . PHP_EOL);

        // Reply with the commands list
        //$this->replyWithMessage(['text' => $response]);
        $this->replyWithMessage(compact('text'));

        // Trigger another command dynamically from within this command
        // When you want to chain multiple commands within one or process the request further.
        // The method supports second parameter arguments which you can optionally pass, By default
        // it'll pass the same arguments that are received for this command originally.
        //$this->triggerCommand('subscribe');
        //
        return response('OK', 200);
    }
}
