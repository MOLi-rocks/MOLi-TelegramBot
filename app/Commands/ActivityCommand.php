<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class ActivityCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "activity";

    /**
     * @var string Command Description
     */
    protected $description = "顯示 MOLi 未來公開活動";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        try {
            $client = new \GuzzleHttp\Client(['base_uri' => 'https://moli.kktix.cc/']);
            $response = $client->request('GET', 'events.json', [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'Accept'     => 'application/json'
                ]
            ]);
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            $this->replyWithChatAction(['action' => Actions::TYPING]);
            $this->replyWithMessage(['text' => '網路連線異常 QAQ']);
            return (new \Illuminate\Http\Response)->setStatusCode(200, 'OK');
        }

        $body = $response->getBody();
        $json = json_decode($body, true);
        $activity = 0;

        foreach ($json['entry'] as $num => $detail) {
            if ( strtotime($detail['published']) > strtotime('now') ) {
                $this->replyWithChatAction(['action' => Actions::TYPING]);

                $this->replyWithMessage([
                    'text' => $detail['title'] . PHP_EOL . '' . PHP_EOL . $detail['content'] . PHP_EOL . '' . PHP_EOL . $detail['url']
                ]);

                $activity++;
            } else break;
        }

        if ($activity == 0) {
            $this->replyWithChatAction(['action' => Actions::TYPING]);
            $this->replyWithMessage(['text' => '最近無排定活動，歡迎在群組挖坑' . PHP_EOL . 'https://www.facebook.com/groups/MOLi.rocks']);
        }
    }
}
