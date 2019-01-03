<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use MOLiBot\LINE_Notify_User;
use Telegram;
use MOLiBot\Published_NCNU_RSS;
use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;
use MOLiBot\Http\Controllers\LINENotifyController;

class NCNU_RSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rss:check {--init}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check New RSS Feed From NCNU';

    /**
     * @var Published_NCNU_RSS
     */
    private $Published_NCNU_RSSModel;
    
    /**
     * Create a new command instance.
     *
     * @param Published_NCNU_RSS $Published_NCNU_RSSModel
     * 
     * @return void
     */
    public function __construct(Published_NCNU_RSS $Published_NCNU_RSSModel)
    {
        parent::__construct();
        
        $this->Published_NCNU_RSSModel = $Published_NCNU_RSSModel;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '1024M');

        Jieba::init(array('mode'=>'default','dict'=>'big'));

        Finalseg::init();

        $json = app('MOLiBot\Http\Controllers\MOLiBotController')->getNCNU_RSS();

        $items = $json['channel']['item'];

        foreach ($items as $item) {
            $hashtag = '';

            if ( !$this->Published_NCNU_RSSModel->where('guid', $item['guid'])->exists() ) {
                $seg_list = Jieba::cut($item['title']);

                foreach($seg_list as $seg_list_item) {
                    $hashtag .= '#' . $seg_list_item . ' ';
                }

                if ($this->option('init')) {
                    $chat_id = env('TEST_CHANNEL');
                } else {
                    $chat_id = env('NEWS_CHANNEL');
                }

                // send to Telegram Channel
                Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => $item['title'] . PHP_EOL . 'http://www.ncnu.edu.tw/ncnuweb/ann/' . $item['link'] . PHP_EOL . PHP_EOL . $hashtag
                ]);

                // send to LINE Notify
                $LNU = LINE_Notify_User::getAllToken(); // LINE Notify Users
                $msg = PHP_EOL .$item['title'] . PHP_EOL . 'http://www.ncnu.edu.tw/ncnuweb/ann/' . $item['link'];
                foreach ($LNU as $key => $at){
                    LINENotifyController::sendMsg($at, $msg);
                    // LINE 限制一分鐘上限 1000 次，做一些保留次數
                    if( ($key+1) % 950 == 0) {
                        sleep(62);
                    }
                }

                $this->Published_NCNU_RSSModel->create(['guid' => $item['guid'], 'title' => $item['title']]);

                // 避免太過頻繁發送
                sleep(5);
            }
        }

        $this->info('Mission Complete!');
    }
}
