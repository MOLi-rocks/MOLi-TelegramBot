<?php

namespace MOLiBot\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Api;

class DeleteWelcomeMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $chatId;

    /**
     * @var string
     */
    protected $memberId;

    /**
     * @var string
     */
    protected $welcomeMessageId;

    /**
     * Create a new job instance.
     *
     * @param string $chatId
     * @param string $memberId
     * @param string $welcomeMessageId
     * @return void
     */
    public function __construct($chatId, $memberId, $welcomeMessageId)
    {
        $this->chatId = $chatId;
        $this->memberId = $memberId;
        $this->welcomeMessageId = $welcomeMessageId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $runDelete = false;

        $blackList = ['left', 'kicked'];

        try {
            $telegram = new Api(config('moli.telegram.bot_token'));

            $memberData = $telegram->getChatMember([
                'chat_id' => $this->chatId,
                'user_id' => $this->memberId
            ]);

            $memberStatus = $memberData->get('status');

            if (in_array($memberStatus, $blackList)) {
                $runDelete = true;
            }
        } catch (\Exception $exception) {
            $code = $exception->getCode();
            $errorMsg = $exception->getMessage();

            if ($code > 0) {
                $runDelete = true;
            } else {
                throw new \Exception($errorMsg);
            }
        }

        if ($runDelete) {
            $telegram->deleteMessage([
                'chat_id' => $this->chatId,
                'message_id' => $this->welcomeMessageId
            ]);
        }
    }
}
