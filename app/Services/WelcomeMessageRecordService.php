<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\WelcomeMessageRecordRepository;

class WelcomeMessageRecordService
{
    private $welcomeMessageRecordRepository;

    public function __construct(WelcomeMessageRecordRepository $welcomeMessageRecordRepository)
    {
        $this->welcomeMessageRecordRepository = $welcomeMessageRecordRepository;
    }

    /**
     * @param integer $chatId
     * @param integer $newChatMemberId
     * @param integer $welcomeMessageId
     * @return int|mixed
     */
    public function addNewRecord($chatId, $newChatMemberId, $welcomeMessageId)
    {
        $joinTimestamp = time();
        $checked = false;

        return $this->welcomeMessageRecordRepository->createRecord(
            $chatId, $newChatMemberId, $welcomeMessageId, $joinTimestamp, $checked
        );
    }
}