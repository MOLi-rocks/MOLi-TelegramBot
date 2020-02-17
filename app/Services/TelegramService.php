<?php

namespace MOLiBot\Services;

use Telegram\Bot\Api;
use MOLiBot\Services\WelcomeMessageRecordService;
use Telegram\Bot\Objects\Message;

class TelegramService
{
    /** @var Api */
    protected $telegram;

    /**
     * @var string
     */
    private $MOLiWelcomeMsg;

    /**
     * @var int
     */
    private $MOLiGroupId;

    /**
     * @var WelcomeMessageRecordService
     */
    private $welcomeMessageRecordService;

    /**
     * TelegramService constructor.
     *
     * @param Api $telegram
     * @param WelcomeMessageRecordService $welcomeMessageRecordService
     */
    public function __construct(Api $telegram,
                                WelcomeMessageRecordService $welcomeMessageRecordService)
    {
        $this->telegram = $telegram;
        $this->welcomeMessageRecordService = $welcomeMessageRecordService;
        $this->MOLiGroupId = config('moli.telegram.group_id');
        $this->MOLiWelcomeMsg = config('moli.telegram.group_welcome_msg');
    }

    /**
     * @param Message $hook
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function sendWelcomeMsg(Message $hook)
    {
        $chatId = $hook->getChat()->getId();
        $memberIsBot = $hook->getNewChatMember()->getIsBot();

        if ($chatId === $this->MOLiGroupId && !$memberIsBot) {
            $welcomeMsg = $this->telegram->sendMessage([
                'chat_id' => $this->MOLiGroupId,
                'reply_to_message_id' => $hook->getMessageId(),
                'disable_web_page_preview' => true,
                'text' => $this->MOLiWelcomeMsg
            ]);

            $newChatMemberId = $hook->getNewChatMember()->getId();
            $welcomeMsgId = $welcomeMsg->getMessageId();
            $this->welcomeMessageRecordService->addNewRecord($chatId, $newChatMemberId, $welcomeMsgId);
        }
    }
}