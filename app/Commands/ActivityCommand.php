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
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://moli.rocks/']);
        $response = $client->request('GET', 'kktix/events.json', [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36)',
                'Accept'     => 'application/json'
            ]
        ]);
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
