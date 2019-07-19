<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;
use Telegram;

use Carbon\Carbon;
use MOLiBot\Services\FuelPriceService;

class GetFuelPriceGap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fuelprice:checkgap {--init}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '計算每週油價價差（排程用）';

    /**
     * @var fuelPriceService
     */
    private $fuelPriceService;

    /**
     * Create a new command instance.
     *
     * @param FuelPriceService $fuelPriceService
     *
     * @return void
     */
    public function __construct(FuelPriceService $fuelPriceService)
    {
        parent::__construct();

        $this->fuelPriceService = $fuelPriceService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = $this->fuelPriceService->calculateGap();

        $tomorrow = Carbon::tomorrow();

        if ($this->option('init')) {
            $chat_id = env('TEST_CHANNEL');
        } else {
            $chat_id = env('MOLi_CHANNEL');
        }

        Telegram::sendMessage([
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => '中油已公告新油價，' . $tomorrow . ' 起：' . PHP_EOL . PHP_EOL .
                '98無鉛汽油' . $result['98無鉛汽油'] . PHP_EOL . PHP_EOL .
                '95無鉛汽油' . $result['95無鉛汽油'] . PHP_EOL . PHP_EOL .
                '92無鉛汽油' . $result['92無鉛汽油'] . PHP_EOL . PHP_EOL .
                '超級柴油' . $result['超級柴油']
        ]);
    }
}
