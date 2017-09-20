<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use DB;
use Telegram;
use Storage;
use DOMDocument;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;
use GuzzleHttp\Cookie\SessionCookieJar;

use MOLiBot\WhoUseWhatCommand;

class EnableNCDRCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "ncdrstart";

    /**
     * @var string Command Description
     */
    protected $description = "重新啟動 NCDR 資料推送";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $update = Telegram::getWebhookUpdates();

        if ( $update->all()['message']['chat']['type'] == 'private' ) {
            if (empty($arguments)) {
                $client = new GuzzleHttpClient();

                try {
                    $cookieJar = new SessionCookieJar('SESSION_STORAGE', true);

                    $pageContents = $client->request('GET', 'https://alerts.ncdr.nat.gov.tw/Captcha.aspx', [
                        'headers' => [
                            'User-Agent' => 'MOLi Bot'
                        ],
                        'cookies' => $cookieJar,
                        'timeout' => 10
                    ]);

                    if ($pageContents->getStatusCode() == '200') {
                        $type = explode("/", $pageContents->getHeader('Content-Type')[0]);

                        $imgpath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

                        if ($type[0] == 'image') {
                            $fileName = 'ncdr_captcha'.rand(11111,99999);

                            Storage::disk('local')->put($fileName.'.'.$type[1], $pageContents->getBody());

                            DB::transaction(function () use ($update) {
                                WhoUseWhatCommand::where('user-id', '=', $update->all()['message']['from']['id'])->delete();

                                WhoUseWhatCommand::create([
                                    'user-id' => $update->all()['message']['from']['id'],
                                    'command' => $this->name
                                ]);
                            });

                            Telegram::sendPhoto([
                                'chat_id' => $update->all()['message']['chat']['id'],
                                'photo' => $imgpath.$fileName.'.'.$type[1],
                                'caption' => '請回傳圖中驗證碼'
                            ]);

                            Storage::disk('local')->delete($fileName.'.'.$type[1]);
                        } else {
                            $this->replyWithMessage(['text' => '找不到圖 GG']);
                        }
                    } else {
                        $this->replyWithMessage(['text' => '網路連線異常 QAQ']);
                    }
                } catch (GuzzleHttpTransferException $e) {
                    $this->replyWithMessage(['text' => '網路連線異常 QAQ']);
                }
            } else {
                WhoUseWhatCommand::where('user-id', '=', $update->all()['message']['from']['id'])->delete();

                Telegram::sendMessage([
                    'chat_id' => $update->all()['message']['chat']['id'],
                    'text' => '$arguments'
                ]);
            }
        } else {
            $this->replyWithChatAction(['action' => Actions::TYPING]);

            Telegram::sendMessage([
                'chat_id' => $update->all()['message']['chat']['id'],
                'text' => '此功能限一對一對話',
                'reply_to_message_id' => $update->all()['message']['message_id']
            ]);
        }

        return response('OK', 200);
    }
}
