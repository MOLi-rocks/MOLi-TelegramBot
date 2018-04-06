<?php

namespace MOLiBot\Commands;

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
        if (empty(env('NCDR_API_KEY'))) {
            $this->replyWithChatAction(['action' => Actions::TYPING]);
            $this->replyWithMessage(['text' => '此服務未啟動']);
            return response('OK', 200); // 強制結束 command
        }

        $baseUrl = 'https://alerts.ncdr.nat.gov.tw';

        $client = new GuzzleHttpClient();

        try {
            $response = $client->request(
                'GET',
                $baseUrl . '/api/datastore?format=json&capcode=WSC&apikey=' . env('NCDR_API_KEY'),
                ['timeout' => 10]
            );
        } catch (GuzzleHttpTransferException $e) {
            $this->replyWithChatAction(['action' => Actions::TYPING]);
            $this->replyWithMessage(['text' => '暫無停班停課資訊']);
            return response('OK', 200); // 強制結束 command
        }

        $info_data = json_decode($response->getBody());

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        if ($info_data['success'] === false || count($info_data['result']) < 1) {
            $this->replyWithMessage(['text' => '暫無停班停課資訊']);
        } else {
            $output_str = '';

            foreach ($info_data['result'] as $result) {
                $output_str .= trim($result['description']) . PHP_EOL;
            }

            $this->replyWithMessage(['text' => $output_str]);
        }

        return response('OK', 200);
    }
}
