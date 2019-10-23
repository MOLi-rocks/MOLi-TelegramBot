<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;

use MOLiBot\Http\Requests\HistoryFuelPriceRequest;

use SoapBox\Formatter\Formatter;
use Telegram;

use MOLiBot\Services\FuelPriceService;
use MOLiBot\Services\NcdrService;
use MOLiBot\Services\NcnuService;
use Log;

class MOLiBotController extends Controller
{
    /** @var \Illuminate\Support\Collection NCDR_to_BOTChannel_list */
    private $NCDR_to_BOTChannel_list;

    /** @var \Illuminate\Support\Collection NCDR_should_mute */
    private $NCDR_should_mute;

    private $fuelPriceService;
    private $ncdrService;
    private $ncnuService;

    /**
     * MOLiBotController constructor.
     * @param FuelPriceService $fuelPriceService
     * @param NcdrService $ncdrService
     * @param NcnuService $ncnuService
     */
    public function __construct(
        FuelPriceService $fuelPriceService,
        NcdrService $ncdrService,
        NcnuService $ncnuService
    ) {
        // 哪些類別的 NCDR 訊息要推到 MOLi 廣播頻道
        $this->NCDR_to_BOTChannel_list = collect([
            '地震',
            '土石流',
            '河川高水位',
            '降雨',
            '停班停課',
            '道路封閉',
            '雷雨',
            '颱風'
        ]);

        // 哪些類別的 NCDR 訊息要靜音
        $this->NCDR_should_mute = collect(['土石流']);

        $this->fuelPriceService = $fuelPriceService;

        $this->ncdrService = $ncdrService;

        $this->ncnuService = $ncnuService;
    }

    /**
     * 回應對 GET / 的請求
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getIndex()
    {
        return redirect('https://moli.rocks');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function connectTester(Request $request)
    {
        // what format to return (json or XML, default to json)
        $format = $request->input('format', 'json');

        if ($format == 'xml') {
            return response('<?xml version="1.0" encoding="UTF-8" ?><Data><Status>true</Status></Data>')
                ->header('Content-Type', 'text/xml');
        } else {
            return response()->json(['Status' => true]);
        }
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNCNU_RSS()
    {
        return $this->ncnuService->getRss();
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNCDR_RSS()
    {
        return $this->ncdrService->getRss();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postNCDR(Request $request)
    {
        //use $request->getContent() to get raw data
        $formatter = Formatter::make($request->getContent(), Formatter::XML);
        $json = $formatter->toArray();

        if ( config('app.log_input') ) {
            Log::info(json_encode($json, JSON_UNESCAPED_UNICODE));
        }

        if ($json['status'] == 'Actual') {
            $channel_to = config('telegram-channel.weather');
            $posted = collect([]);

            if (!isset($json['info']['event'])) {// info 是個 array
                foreach ($json['info'] as $info) {
                    if ($this->NCDR_to_BOTChannel_list->contains($info['event']) && isset($info['description'])) {
                        if (!$posted->contains($info['description'])) {// 如果沒發過的話發一下
                            if (is_array($info['description'])) {
                                $des = '';

                                if ($info['event'] == '颱風') {
                                    foreach ($info['description']['section'] as $typhoon_warning) {
                                        $des .= $typhoon_warning;
                                    }
                                }
                            } else {
                                $des = $info['description'];
                            }

                            if ($this->NCDR_should_mute->contains($info['event'])) {
                                $send_msg = [
                                    'chat_id' => $channel_to,
                                    'text' => '#' . $info['event'] . PHP_EOL . $info['senderName'] . '：' . $info['headline'] . PHP_EOL . $des,
                                    'disable_notification' => true
                                ];
                            } else {
                                $send_msg = [
                                    'chat_id' => $channel_to,
                                    'text' => '#' . $info['event'] . PHP_EOL . $info['senderName'] . '：' . $info['headline'] . PHP_EOL . $des
                                ];
                            }

                            Telegram::sendMessage($send_msg);

                            $posted->push($info['description']);// 發完加入已發布清單
                        }
                    }
                }
            } else {// info 是單個
                if ($this->NCDR_to_BOTChannel_list->contains($json['info']['event']) && isset($json['info']['description'])) {
                    if (is_array($json['info']['description'])) {
                        $des = '';

                        if ($json['info']['event'] == '颱風') {
                            foreach ($json['info']['description']['section'] as $typhoon_warning) {
                                $des .= $typhoon_warning;
                            }
                        }
                    } else {
                        $des = $json['info']['description'];
                    }

                    if ($this->NCDR_should_mute->contains($json['info']['event'])) {
                        $send_msg = [
                            'chat_id' => $channel_to,
                            'text' => '#' . $json['info']['event'] . PHP_EOL . $json['info']['senderName'] . '：' . $json['info']['headline'] . PHP_EOL . $des,
                            'disable_notification' => true
                        ];
                    } else {
                        $send_msg = [
                            'chat_id' => $channel_to,
                            'text' => '#' . $json['info']['event'] . PHP_EOL . $json['info']['senderName'] . '：' . $json['info']['headline'] . PHP_EOL . $des
                        ];
                    }

                    Telegram::sendMessage($send_msg);
                }
            }
        }

        return response('<?xml version="1.0" encoding="UTF-8" ?><Data><Status>true</Status></Data>')
            ->header('Content-Type', 'text/xml');
    }

    /**
     * @param null $keyword
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStaffContact($keyword = NULL)
    {
        return $this->ncnuService->getStaffContact($keyword);
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFuelPrice()
    {
        return $this->fuelPriceService->getLiveFuelPrice();
    }

    /**
     * @param HistoryFuelPriceRequest $request
     * @return int|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHistoryFuelPrice(HistoryFuelPriceRequest $request)
    {
        $prodId = $request->input('prodid');

        return $this->fuelPriceService->getHistoryFuelPrice($prodId);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function anyRoute()
    {
        return redirect('https://moli.rocks');
    }
}
