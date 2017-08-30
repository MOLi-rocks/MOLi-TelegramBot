<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;
use Telegram;

use Carbon\Carbon;
use MOLiBot\FuelPrice;

class GetFuelPriceGap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fuelprice:checkgap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '計算每週油價價差（排程用）';

    /**
     * @var FuelPrice
     */
    private $FuelPriceModel;

    /**
     * Create a new command instance.
     *
     * @param FuelPrice $FuelPriceModel
     *
     * @return void
     */
    public function __construct(FuelPrice $FuelPriceModel)
    {
        parent::__construct();

        $this->FuelPriceModel = $FuelPriceModel;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $datas = app('MOLiBot\Http\Controllers\MOLiBotController')->getFuelPrice(true);

        $result = array();

        foreach ($datas as $data) {
            if ( $this->FuelPriceModel->where('name', '=', $data['產品名稱'])->where('start_at', '=', $data['牌價生效時間'])->exists() ) {
                $result += array($data['產品名稱'] => '不調整 (' . $data['參考牌價'] . ')');
            } else {
                $lasttime = $this->FuelPriceModel->where('name', '=', $data['產品名稱'])
                    ->orderBy('start_at', 'desc')
                    ->first();

                if ($lasttime) {
                    $lasttimeprice = $lasttime->price - 0;
                } else {
                    $lasttimeprice = 0;
                }

                $this->FuelPriceModel->create([
                    'name' => $data['產品名稱'],
                    'unit' => $data['計價單位'],
                    'price' => $data['參考牌價'],
                    'start_at' => $data['牌價生效時間']
                ]);

                $pricegap = $data['參考牌價'] - $lasttimeprice;

                if ($pricegap > 0) {
                    $result += array($data['產品名稱'] => ' 將 調漲 ' . $pricegap . $data['計價單位'] . '(' . $lasttimeprice . '->' . $data['參考牌價'] . ')');
                } else if ($pricegap < 0) {
                    $result += array($data['產品名稱'] => ' 將 調降 ' . abs($pricegap) . $data['計價單位'] . '(' . $lasttimeprice . '->' . $data['參考牌價'] . ')');
                } else {
                    $result += array($data['產品名稱'] => ' 將 不調整 (' . $data['參考牌價'] . ')');
                }
            }
        }

        //$this->info(print_r($result));
        $tomorrow = Carbon::tomorrow();
        Telegram::sendMessage([
            'chat_id' => env('MOLi_CHANNEL'),
            'text' => '中油已公告新油價，' . $tomorrow . ' 起：' . PHP_EOL . PHP_EOL .
                '98無鉛汽油' . $result['98無鉛汽油'] . PHP_EOL . PHP_EOL .
                '95無鉛汽油' . $result['95無鉛汽油'] . PHP_EOL . PHP_EOL .
                '92無鉛汽油' . $result['92無鉛汽油'] . PHP_EOL . PHP_EOL .
                '超級柴油' . $result['超級柴油']
        ]);
    }
}
