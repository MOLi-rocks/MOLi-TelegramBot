<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Exception;
use MOLiBot\Services\LINENotifyService;
use MOLiBot\Services\NcnuService;
use MOLiBot\Services\TelegramService;
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
     * @var NcnuService
     */
    private $ncnuService;

    /**
     * @var LINENotifyService
     */
    private $LINENotifyService;

    /**
     * @var telegramService
     */
    private $telegramService;
    
    /**
     * Create a new command instance.
     *
     * @param NcnuService $ncnuService
     * @param LINENotifyService $LINENotifyService
     * @param TelegramService $telegramService
     * @return void
     */
    public function __construct(NcnuService $ncnuService,
                                LINENotifyService $LINENotifyService,
                                TelegramService $telegramService)
    {
        parent::__construct();
        
        $this->ncnuService = $ncnuService;
        $this->LINENotifyService = $LINENotifyService;
        $this->telegramService = $telegramService;
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

            $contents = $this->ncnuService->getRss();

            $items = $contents['channel']['item'];

            foreach ($items as $item) {
                $hashtag = '';

                if (!$this->ncnuService->checkRssPublished($item['guid'])) {
                    if ($this->option('init')) {
                        $this->ncnuService->storePublishedRss($item['guid']);
                    } else {
                        $seg_list = Jieba::cut($item['title']);

                        foreach ($seg_list as $seg_list_item) {
                            $hashtag .= '#' . $seg_list_item . ' ';
                        }

                        $rawMsg = $item['title'] . PHP_EOL . 'http://www.ncnu.edu.tw/ncnuweb/ann/' . $item['link'];

                        // send to Telegram Channel
                        $this->telegramService->sendMessage(
                            config('telegram-channel.ncnu_news'),
                            $rawMsg . PHP_EOL . PHP_EOL . $hashtag,
                            null,
                            true
                        );

                        // send to LINE Notify
                        $lineMsg = PHP_EOL . $rawMsg;
                        $this->LINENotifyService->sendMsgToAll($lineMsg);

                        $this->ncnuService->storePublishedRss($item['guid']);

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
