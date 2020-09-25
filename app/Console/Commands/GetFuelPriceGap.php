<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use MOLiBot\Services\FuelPriceService;
use MOLiBot\Services\TelegramService;

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
     * @var telegramService
     */
    private $telegramService;

    /**
     * Create a new command instance.
     *
     * @param FuelPriceService $fuelPriceService
     * @param TelegramService $telegramService
     *
     * @return void
     */
    public function __construct(FuelPriceService $fuelPriceService,
                                TelegramService $telegramService)
    {
        parent::__construct();

        $this->fuelPriceService = $fuelPriceService;
        $this->telegramService = $telegramService;
    }

    /**
     * Execute the console command.
     *
     * @return integer
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle()
    {
        $result = $this->fuelPriceService->calculateGap();

        $tomorrow = Carbon::tomorrow();

        if ($this->option('init')) {
            $chatId = config('telegram-channel.test');
        } else {
            $chatId = config('telegram-channel.MOLi');
        }

        $text = '中油已公告新油價，' . $tomorrow . ' 起：' . PHP_EOL . PHP_EOL .
            '98無鉛汽油' . $result['98無鉛汽油'] . PHP_EOL . PHP_EOL .
            '95無鉛汽油' . $result['95無鉛汽油'] . PHP_EOL . PHP_EOL .
            '92無鉛汽油' . $result['92無鉛汽油'] . PHP_EOL . PHP_EOL .
            '超級柴油' . $result['超級柴油'];

        $this->telegramService->sendMessage(
            $chatId,
            $text,
            'HTML',
            true
        );

        return 0;
    }
}
