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
    protected $description = "Check status of MOLi Door";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $firebase = new \Firebase\FirebaseLib(env('FIREBASE'));
        $status = $firebase->get('/status');

        if ($status == 1) {
            $reply = 'MOLi 關門中';
        } else if ($status == 0) {
            $reply = 'MOLi 開門中';
        } else {
            $reply = '我現在 GG 中 Orz';
        }
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        $this->replyWithMessage(['text' => $reply]);
    } 
}
