<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use MOLiBot\Published_KKTIX;

class MOLiDay_Events extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kktix:check {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check New MOLiDay Event From KKTIX（add --dry-run for testing mode）';

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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://moli.kktix.cc/events.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["cache-control: no-cache", "user-agent: MOLi Bot"]);
        $fileContents = curl_exec($ch);
        if (curl_errno($ch) == 28) {
            //Log CURL Timeout message
        }
        curl_close($ch);

        $json = json_decode($fileContents);
        $events = $json->entry;

        if ($this->option('dry-run')) {
            $headers = ['活動標題', '活動簡介', '活動地點', '報名網址'];
            
            $datas = [];
            
            foreach ($events as $event) {
                if ( !Published_KKTIX::where('url', $event->url)->exists() ) {
                    $datas += ['活動標題' => $event->title,
                               '活動簡介' => $event->summary,
                               '活動地點' => $event->content,
                               '報名網址' => $event->url];
                }
            }
            
            $this->table($headers, $datas);
        } else {
            foreach ($events as $event) {
                if ( !Published_KKTIX::where('url', $event->url)->exists() ) {
                    Telegram::sendMessage([
                        'chat_id' => env('MOLi_CHANNEL'),
                        'text' => 'MOLiDay 新活動：' . PHP_EOL . $event->title . PHP_EOL . PHP_EOL .
                            '活動簡介：' . PHP_EOL . $event->summary . PHP_EOL . PHP_EOL .
                            '活動地點：' . PHP_EOL . $event->content . PHP_EOL . PHP_EOL .
                            '報名網址：' . PHP_EOL . $event->url . PHP_EOL . PHP_EOL
                    ]);

                    Published_KKTIX::create(['url' => $event->url, 'title' => $event->title]);

                    sleep(5);
                }
            }

            $this->info('Mission Complete!');
        }
    }
}
