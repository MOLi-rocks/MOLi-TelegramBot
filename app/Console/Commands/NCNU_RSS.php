<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use Storage;

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
    protected $description = 'Check New RSS Feed From NCNU';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $json = app('MOLiBot\Http\Controllers\MOLiBotController')->getNCNU_RSS();
        $items = $json['channel']['item'];

        if (Storage::disk('local')->has('RSS_published')) {
            $content = Storage::disk('local')->get('RSS_published');
        } else {
            Storage::disk('local')->put('RSS_published', '[""]');
            $content = Storage::disk('local')->get('RSS_published');
        }

        $published = json_decode($content);
        $publishedArray = array();
        $getChanged = 'N';

        foreach ($items as $item) {
            $publishedArray[] = $item['guid'];
            foreach ($published as $publishedguid) {
                if ($item['guid'] == $publishedguid) {
                    $news = 'N';
                    break;
                } else {
                    $news = 'Y';
                }
            }
            if ($news == 'Y') {
                $getChanged = 'Y';
                Telegram::sendMessage([
                    'chat_id' => env('NEWS_CHANNEL'),
                    'text' => $item['title'] . PHP_EOL . 'http://www.ncnu.edu.tw/ncnuweb/ann/' . $item['link']
                ]);
                sleep(5);
            }
        }

        if ($getChanged == 'Y') {
            Storage::disk('local')->delete('RSS_published');
            sleep(1);
            Storage::disk('local')->put('RSS_published', json_encode($publishedArray));
        }

        $this->info('Mission Complete!');
    }
}
