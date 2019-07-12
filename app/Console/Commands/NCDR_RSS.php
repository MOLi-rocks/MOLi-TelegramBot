<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use Exception;
use MOLiBot\Services\NcdrRssService;

class NCDR_RSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ncdr:rss-check {--init}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check New RSS Feed From NCDR';

    /**
     * @var ncdrRssService
     */
    private $ncdrRssService;

    /** @var \Illuminate\Support\Collection NCDR_to_BOTChannel_list */
    private $NCDR_to_BOTChannel_list;

    /** @var \Illuminate\Support\Collection NCDR_should_mute */
    private $NCDR_should_mute;
    
    /**
     * Create a new command instance.
     *
     * @param NcdrRssService $ncdrRssService
     * 
     * @return void
     */
    public function __construct(NcdrRssService $ncdrRssService)
    {
        parent::__construct();
        
        $this->ncdrRssService = $ncdrRssService;

        // 哪些類別的 NCDR 訊息要推到 MOLi 廣播頻道
        $this->NCDR_to_BOTChannel_list = collect([
            '地震',
            '土石流',
            '河川高水位',
            '降雨',
            '停班停課',
            '道路封閉',
            '雷雨',
            '颱風'
        ]);

        // 哪些類別的 NCDR 訊息要靜音
        $this->NCDR_should_mute = collect([
            '土石流'
        ]);
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
            $contents = $this->ncdrRssService->getNcdrRss();

            $items = $contents['entry'];

            $nowListId = [];

            foreach ($items as $item) {
                $itemId = $item['id'];

                array_push($nowListId, $itemId);

                if (!$this->ncdrRssService->checkRssPublished($itemId)) {
                    if ($this->option('init')) {
                        $chat_id = env('TEST_CHANNEL');
                    } else {
                        $chat_id = env('WEATHER_CHANNEL');
                    }

                    $category = $item['category']['@term'];

                    if ($this->NCDR_to_BOTChannel_list->contains($category)) {
                        Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text'    => trim($item['summary']['#text']) . PHP_EOL . '#' . $category
                        ]);
                    }

                    $this->ncdrRssService->storePublishedRss($itemId, $category);

                    sleep(5);
                }
            }

            $this->ncdrRssService->deletePublishedRecordWithExcludeId($nowListId);

            $this->info('Mission Complete!');
            return;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return;
        }
    }
}
