<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;

class FuckCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'fuck';

    /**
     * @var string Command Description
     */
    protected $description = '骂人专用';

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $baseUrl = 'https://nmsl.shadiao.app/';

        $client = new GuzzleHttpClient();

        try {
            $response = $client->request(
                'GET',
                $baseUrl,
                ['timeout' => 20]
            );
        } catch (GuzzleHttpTransferException $e) {
            $this->replyWithChatAction(['action' => Actions::TYPING]);
            $this->replyWithMessage(['text' => '呵呵！']);
            return response('OK', 200); // 強制結束 command
        }

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $this->replyWithMessage(['text' => $response->getBody()]);

        return response('OK', 200);
    }
}
