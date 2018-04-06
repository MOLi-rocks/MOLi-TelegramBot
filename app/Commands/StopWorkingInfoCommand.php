<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StopWorkingInfoCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'stopworkinginfo';

    /**
     * @var string Command Description
     */
    protected $description = '停班停課查詢（颱風假查詢）';

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->replyWithMessage(['text' => '我也不知道能不能放假QQ']);

        return response('OK', 200);
    }
}
