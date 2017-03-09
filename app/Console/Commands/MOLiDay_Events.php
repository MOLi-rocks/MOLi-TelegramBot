<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use Storage;

class MOLiDay_Events extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kktix:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check New MOLiDay Event From KKTIX';

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
        $events = $json['entry'];

        if (Storage::disk('local')->has('KKTIX_published')) {
            $content = Storage::disk('local')->get('KKTIX_published');
        } else {
            Storage::disk('local')->put('KKTIX_published', '[""]');
            $content = Storage::disk('local')->get('KKTIX_published');
        }

        $published = json_decode($content);
        $publishedArray = array();
        $getChanged = 'N';

        foreach ($events as $event) {
            $publishedArray[] = $event->url;
            foreach ($published as $publishedurl) {
                if ($event->url == $publishedurl) {
                    $new = 'N';
                    break;
                } else {
                    $new = 'Y';
                }
            }
            if ($new == 'Y') {
                $getChanged = 'Y';
                Telegram::sendMessage([
                    'chat_id' => env('MOLi_CHANNEL'),
                    'text' => 'MOLiDay 新活動：' . PHP_EOL . $event->title . PHP_EOL . PHP_EOL .
                        '活動簡介：' . PHP_EOL . $event->summary . PHP_EOL . PHP_EOL .
                        '活動地點：' . PHP_EOL . $event->content . PHP_EOL . PHP_EOL .
                        '報名網址：' . PHP_EOL . $event->url . PHP_EOL . PHP_EOL
                ]);
                sleep(5);
            }
        }

        if ($getChanged == 'Y') {
            Storage::disk('local')->delete('KKTIX_published');
            sleep(1);
            Storage::disk('local')->put('KKTIX_published', json_encode($publishedArray));
        }

        $this->info('Mission Complete!');
    }
}
