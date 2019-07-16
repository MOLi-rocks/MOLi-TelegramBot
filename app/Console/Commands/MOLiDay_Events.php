<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use Exception;
use MOLiBot\Services\MOLiDayService;

class MOLiDay_Events extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MOLi:kktix-event-check {--dry-run} {--init}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check New MOLiDay Event From KKTIX（add --dry-run for testing mode）';

    /**
     * @var MOLiDayService
     */
    private $MOLiDayService;
    
    /**
     * Create a new command instance.
     *
     * @param MOLiDayService $MOLiDayService
     * 
     * @return void
     */
    public function __construct(MOLiDayService $MOLiDayService)
    {
        parent::__construct();
        
        $this->MOLiDayService = $MOLiDayService;
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
            $fileContents = $this->MOLiDayService->getEvents();

            $events = $fileContents['entry'];
            
            if (empty($events)) {
                throw new Exception('Event is empty');
            }

            if ($this->option('dry-run')) {
                $headers = ['活動標題', '活動簡介', '活動地點', '報名網址'];

                $datas = [];

                foreach ($events as $event) {
                    $datas[] = [
                        '活動標題' => $event['title'],
                        '活動簡介' => $event['summary'],
                        '活動地點' => $event['content'],
                        '報名網址' => $event['url']
                    ];
                }

                $this->table($headers, $datas);
            } else {
                foreach ($events as $event) {
                    if (!$this->MOLiDayService->checkEventPublished($event['url'])) {
                        if ($this->option('init')) {
                            $chat_id = env('TEST_CHANNEL');
                        } else {
                            $chat_id = env('MOLi_CHANNEL');
                        }

                        Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text'    => 'MOLiDay 新活動：' . PHP_EOL . $event['title'] . PHP_EOL . PHP_EOL .
                                '活動簡介：' . PHP_EOL . $event['summary'] . PHP_EOL . PHP_EOL .
                                '活動地點：' . PHP_EOL . $event['content'] . PHP_EOL . PHP_EOL .
                                '報名網址：' . PHP_EOL . $event['url'] . PHP_EOL . PHP_EOL
                        ]);

                        $this->MOLiDayService->storePublishedEvent($event);

                        sleep(5);
                    }
                }

                $this->info('Mission Complete!');
            }

            return;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return;
        }
    }
}
