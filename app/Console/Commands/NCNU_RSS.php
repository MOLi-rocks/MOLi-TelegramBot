<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use MOLiBot\Services\LINENotifyService;
use MOLiBot\Services\NcnuRssService;
use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

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
     * @var NcnuRssService
     */
    private $ncnuRssService;

    /**
     * @var LINENotifyService
     */
    private $LINENotifyService;
    
    /**
     * Create a new command instance.
     *
     * @param NcnuRssService $ncnuRssService
     * @param LINENotifyService $LINENotifyService
     * @return void
     */
    public function __construct(NcnuRssService $ncnuRssService, LINENotifyService $LINENotifyService)
    {
        parent::__construct();
        
        $this->ncnuRssService = $ncnuRssService;

        $this->LINENotifyService = $LINENotifyService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '1024M');

        Jieba::init([
            'mode'=>'default',
            'dict'=>'big'
        ]);

        Finalseg::init();

        $json = $this->ncnuRssService->getNcnuRss();

        $items = $json['channel']['item'];

        foreach ($items as $item) {
            $hashtag = '';

            if ( !$this->ncnuRssService->checkRssPublished($item['guid']) ) {
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
                $LNU = $this->LINENotifyService->getAllToken(); // LINE Notify Users
                $msg = PHP_EOL .$item['title'] . PHP_EOL . 'http://www.ncnu.edu.tw/ncnuweb/ann/' . $item['link'];
                foreach ($LNU as $key => $token){
                    try {
                        $this->LINENotifyService->sendMsg($token, $msg);
                    } catch (\Exception $e) {
                        $this->LINENotifyService->updateStatus($token);
                    }

                    // LINE 限制一分鐘上限 1000 次，做一些保留次數
                    if( ($key+1) % 950 == 0) {
                        sleep(62);
                    }
                }

                $this->ncnuRssService->storePublishedRss($item['guid'], $item['title']);

                // 避免太過頻繁發送
                sleep(5);
            }
        }

        $this->info('Mission Complete!');
    }
}
