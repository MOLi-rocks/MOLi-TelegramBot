<?php

namespace MOLiBot\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use SoapBox\Formatter\Formatter;
use Telegram;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\TokenGenerator::class,
        Commands\TokenList::class,
        Commands\TokenDelete::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->call(function () {
            $fileContents = file_get_contents('http://www.ncnu.edu.tw/ncnuweb/ann/RSS.aspx');
            $formatter = Formatter::make($fileContents, Formatter::XML);
            $json = $formatter->toArray();
            $items = $json['channel']['item'];
            $result = array();
            foreach ($items as $item) {
                if (strtotime($item['pubDate']) - strtotime('now') / 60 <= 60) {
                    Telegram::sendMessage([
                        'chat_id' => '@ncnu_news',
                        'text' => $item['title'] . PHP_EOL . 'http://www.ncnu.edu.tw/ncnuweb/ann/' . $item['link']
                    ]);
                }
            }
        })->hourly();
    }
}
