<?php

namespace MOLiBot\Commands;

use MOLiBot\Services\NcdrService;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;

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
        $ncdrService = app('MOLiBot\Services\NcdrService');

        $data = $ncdrService->getStopWorkingInfo();

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        switch ($data['status']) {
            case 0:
                $this->replyWithMessage(['text' => '暫無停班停課資訊']);
                break;

            case 1:
                $this->replyWithMessage(['text' => $data['data']]);
                break;

            default:
                $this->replyWithMessage(['text' => '無法取得停班停課資訊']);
        }

        return response('OK', 200);
    }
}
