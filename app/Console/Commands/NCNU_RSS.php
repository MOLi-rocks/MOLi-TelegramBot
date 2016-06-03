<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use SoapBox\Formatter\Formatter;
use Telegram;

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
        
        foreach ($items as $item) {
            if (strtotime($item['pubDate']) - strtotime('now') / 60 <= 60) {
                Telegram::sendMessage([
                    'chat_id' => '@ncnu_news',
                    'text' => $item['title'] . PHP_EOL . 'http://www.ncnu.edu.tw/ncnuweb/ann/' . $item['link']
                ]);
            } else {
                $this->info('Nothing to send!');
                break;
            }
            sleep(5);
        }
    }
}
