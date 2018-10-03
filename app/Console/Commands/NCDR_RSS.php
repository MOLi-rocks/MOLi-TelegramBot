<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use MOLiBot\Published_NCDR_RSS;
use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

class NCDR_RSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ncdr:check {--init}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check New RSS Feed From NCDR';

    /**
     * @var Published_NCDR_RSS
     */
    private $Published_NCDR_RSSModel;

    /** @var \Illuminate\Support\Collection NCDR_to_BOTChannel_list */
    private $NCDR_to_BOTChannel_list;

    /** @var \Illuminate\Support\Collection NCDR_should_mute */
    private $NCDR_should_mute;
    
    /**
     * Create a new command instance.
     *
     * @param Published_NCDR_RSS $Published_NCNU_RSSModel
     * 
     * @return void
     */
    public function __construct(Published_NCDR_RSS $Published_NCDR_RSSModel)
    {
        parent::__construct();
        
        $this->Published_NCDR_RSSModel = $Published_NCDR_RSSModel;

        $this->NCDR_to_BOTChannel_list = collect(['地震', '土石流', '河川高水位', '降雨', '停班停課', '道路封閉', '雷雨', '颱風']); // 哪些類別的 NCDR 訊息要推到 MOLi 廣播頻道

        $this->NCDR_should_mute = collect(['土石流']); // 哪些類別的 NCDR 訊息要靜音
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $json = app('MOLiBot\Http\Controllers\MOLiBotController')->getNCDR_RSS();

        $items = $json['entry'];

        foreach ($items as $item) {
            $category = $item['category']['@attributes']['term'];

            if ( !$this->Published_NCDR_RSSModel->where('id', $item['id'])->exists() ) {
                if ($this->option('init')) {
                    $chat_id = env('TEST_CHANNEL');
                } else {
                    $chat_id = env('WEATHER_CHANNEL');
                }

                if ($this->NCDR_to_BOTChannel_list->contains($category)) {
                    Telegram::sendMessage([
                        'chat_id' => $chat_id,
                        'text' => $item['summary'] . PHP_EOL . PHP_EOL . '#' . $category
                    ]);
                }

                $this->Published_NCDR_RSSModel->create(['id' => $item['id'], 'category' => $category]);

                sleep(5);
            }
        }

        $this->info('Mission Complete!');
    }
}
