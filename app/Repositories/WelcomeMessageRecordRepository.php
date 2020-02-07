<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\WelcomeMessageRecord;

class WelcomeMessageRecordRepository
{
    private $welcomeMessageRecordModel;

    /**
     * WelcomeMessageRecordRepository constructor.
     * @param WelcomeMessageRecord $welcomeMessageRecordModel
     */
    public function __construct(WelcomeMessageRecord $welcomeMessageRecordModel)
    {
        $this->welcomeMessageRecordModel = $welcomeMessageRecordModel;
    }

    /**
     * @param integer $chatId
     * @param integer $memberId
     * @param integer $welcomeMessageId
     * @param integer $joinTimestamp
     * @param bool $checked
     * @return mixed
     */
    public function createRecord($chatId,
                                 $memberId,
                                 $welcomeMessageId,
                                 $joinTimestamp,
                                 $checked = false)
    {
        return $this->welcomeMessageRecordModel->create([
            'chat_id' => $chatId,
            'member_id' => $memberId,
            'welcome_message_id' => $welcomeMessageId,
            'checked' => $checked,
            'join_at' => $joinTimestamp,
        ]);
    }

    public function getLastRecord()
    {
        return $this->welcomeMessageRecordModel
            ->orderBy('join_at', 'desc')
            ->first();
    }

    public function getUncheckedRecord()
    {
        return $this->welcomeMessageRecordModel
            ->where('checked', '=', 0)
            ->orderBy('join_at', 'desc')
            ->first();
    }

    public function purgeOlderCheckedRecord()
    {
        // keep latest record
        /*
         * DELETE FROM `chat`
            WHERE id NOT IN (
                SELECT id
                FROM (
                    SELECT id
                    FROM `chat`
                    ORDER BY id DESC
                    LIMIT 50
                ) foo
            );
         */
    }
}