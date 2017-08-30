<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use MOLiBot\Published_NCNU_RSS;
use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

class NCNU_RSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rss:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check New RSS Feed From NCNU（排程用）';

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

                Telegram::sendMessage([
                    'chat_id' => env('NEWS_CHANNEL'),
                    'text' => $item['title'] . PHP_EOL . 'http://www.ncnu.edu.tw/ncnuweb/ann/' . $item['link'] . PHP_EOL . PHP_EOL . $hashtag
                ]);

                $this->Published_NCNU_RSSModel->create(['guid' => $item['guid'], 'title' => $item['title']]);

                sleep(5);
            }
        }

        $this->info('Mission Complete!');
    }
}
