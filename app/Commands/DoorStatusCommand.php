<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class DoorStatusCommand extends Command
{
   /**
     * @var string Command Name
     */
    protected $name = "status";

    /**
     * @var string Command Description
     */
    protected $description = "現在 MOLi 是開的還是關的呢？";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $firebase = new \Firebase\FirebaseLib(env('FIREBASE'));
        $status = $firebase->get('/status');
        if ( $status ) {
            if ($status == '1') {
                $reply = 'MOLi 現在 關門中';
            } else if ($status == '0') {
                $reply = 'MOLi 現在 開門中';
            } else {
                $reply = '門壞了，猴子們正在努力解決問題！';
            }
        } else {
            $reply = '網路狀況不佳，不知道門怎麼了 ~_~';
        }

        $this->replyWithChatAction(['action' => Actions::TYPING]);
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        $this->replyWithMessage(['text' => $reply]);
    } 
}
