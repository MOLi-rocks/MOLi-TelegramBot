<?php

namespace MOLiBot\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use DB;
use Telegram;
use MOLiBot\Models\WhoUseWhatCommand;
use MOLiBot\Services\NcnuStaffContactService;

class SearchStaffContactCommand extends Command
{
    /**
     * @var string Command Name
     */

    protected $name = 'staffcontact';

    /**
     * @var string Command Description
     */

    protected $description = '使用關鍵字搜尋暨大教職員聯絡資訊(限私訊使用)';

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $update = Telegram::getWebhookUpdates();
        $input = $update->all();

        if ( $input['message']['chat']['type'] == 'private' ) {
            if (empty($arguments)) {
                $this->replyWithChatAction(['action' => Actions::TYPING]);

                Telegram::sendMessage([
                    'chat_id' => $input['message']['chat']['id'],
                    'text' => '請回覆想查詢的關鍵字(不支援多條件搜尋)',
                    'reply_to_message_id' => $input['message']['message_id']
                ]);

                DB::transaction(function () use ($input) {
                    WhoUseWhatCommand::where('user-id', '=', $input['message']['from']['id'])->delete();

                    WhoUseWhatCommand::create([
                        'user-id' => $input['message']['from']['id'],
                        'command' => $this->name
                    ]);
                });

                return response('OK', 200); // 強制結束 command
            }

            $ncnuService = app('MOLiBot\Services\NcnuService');

            $json = $ncnuService->getStaffContact($arguments);

            $text = '';

            $count = 0;

            if (count($json) <= 1) {
                $this->replyWithChatAction(['action' => Actions::TYPING]);

                Telegram::sendMessage([
                    'chat_id' => $input['message']['chat']['id'],
                    'text' => '查無資料 QQ',
                    'reply_to_message_id' => $input['message']['message_id']
                ]);

                WhoUseWhatCommand::where('user-id', '=', $input['message']['from']['id'])->delete();

                return response('OK', 200);
            }

            if (count($json) > 12) {
                $text .= '結果超過 10 筆，建議使用更精確關鍵字搜尋或至 http://ccweb1.ncnu.edu.tw/telquery/StaffQuery.asp 直接搜尋'
                    . PHP_EOL . PHP_EOL . PHP_EOL;
            }

            foreach ($json as $index => $items) {
                if ($index !== 0 && $count < 10) {
                    $text .= $json[0][0] . ': ' . $json[$index][0] . PHP_EOL .
                             $json[0][2] . ': ' . $json[$index][2] . PHP_EOL .
                             $json[0][4] . ': ' . $json[$index][4] . PHP_EOL .
                             $json[0][6] . ': ' . $json[$index][6] . PHP_EOL .
                             $json[0][7] . ': ' . $json[$index][7] . PHP_EOL . PHP_EOL . PHP_EOL;
                    $count++;
                }
            }

            $this->replyWithChatAction(['action' => Actions::TYPING]);

            Telegram::sendMessage([
                'chat_id' => $input['message']['chat']['id'],
                'text' => $text,
                'reply_to_message_id' => $input['message']['message_id']
            ]);

            WhoUseWhatCommand::where('user-id', '=', $input['message']['from']['id'])->delete();
        } else {
            $this->replyWithChatAction(['action' => Actions::TYPING]);

            Telegram::sendMessage([
                'chat_id' => $input['message']['chat']['id'],
                'text' => '此功能限一對一對話',
                'reply_to_message_id' => $input['message']['message_id']
            ]);

            return response('OK', 200); // 強制結束 command
        }

        return response('OK', 200);
    }
}
