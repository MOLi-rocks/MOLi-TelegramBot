<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use SoapBox\Formatter\Formatter;
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
        $fileContents = file_get_contents('http://www.ncnu.edu.tw/ncnuweb/ann/RSS.aspx');
        $formatter = Formatter::make($fileContents, Formatter::XML);
        $json = $formatter->toArray();
        $items = $json['channel']['item'];

        if (Storage::disk('local')->has('RSS_stopFlag')) {
            $end = Storage::disk('local')->get('RSS_stopFlag');
        } else {
            Storage::disk('local')->put('RSS_stopFlag', '0');
            $end = Storage::disk('local')->get('RSS_stopFlag');
        }

        $start = $items[0]['guid'];

        foreach ($items as $item) {
            if ($item['guid'] != $end) {
                Telegram::sendMessage([
                    'chat_id' => '@ncnu_news',
                    'text' => $item['title'] . PHP_EOL . 'http://www.ncnu.edu.tw/ncnuweb/ann/' . $item['link']
                ]);
            } else {
                break;
            }
            sleep(5);
        }
        Storage::disk('local')->delete('RSS_stopFlag');
        sleep(1);
        Storage::disk('local')->put('RSS_stopFlag', $start);
        $this->info('Mission Complete!');
    }
}
