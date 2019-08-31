<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use Exception;
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
    protected $signature = 'ncnu:rss-check {--init}';

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
     * @throws
     */
    public function handle()
    {
        try {
            ini_set('memory_limit', '1024M');

            Jieba::init([
                'mode' => 'default',
                'dict' => 'big'
            ]);

            Finalseg::init();

            $contents = $this->ncnuRssService->getNcnuRss();

            $items = $contents['channel']['item'];

            foreach ($items as $item) {
                $hashtag = '';

                if (!$this->ncnuRssService->checkRssPublished($item['guid'])) {
                    if ($this->option('init')) {
                        $this->ncnuRssService->storePublishedRss($item['guid']);
                    } else {
                        $seg_list = Jieba::cut($item['title']);

                        foreach ($seg_list as $seg_list_item) {
                            $hashtag .= '#' . $seg_list_item . ' ';
                        }

                        $rawMsg = $item['title'] . PHP_EOL . 'http://www.ncnu.edu.tw/ncnuweb/ann/' . $item['link'];

                        // send to Telegram Channel
                        Telegram::sendMessage([
                            'chat_id' => config('telegram-channel.ncnu_news'),
                            'text'    => $rawMsg . PHP_EOL . PHP_EOL . $hashtag
                        ]);

                        // send to LINE Notify
                        $lineMsg = PHP_EOL . $rawMsg;
                        $this->LINENotifyService->sendMsgToAll($lineMsg);

                        $this->ncnuRssService->storePublishedRss($item['guid']);

                        // 避免太過頻繁發送
                        sleep(5);
                    }
                }
            }

            $this->info('Mission Complete!');
            return;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return;
        }
    }
}
