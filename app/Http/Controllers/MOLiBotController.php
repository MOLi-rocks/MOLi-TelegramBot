<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use SoapBox\Formatter\Formatter;
use Telegram;

use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;

use Log;

class MOLiBotController extends Controller
{
    /** @var \Illuminate\Support\Collection NCDR_to_BOTChannel_list */
    private $NCDR_to_BOTChannel_list;

    /** @var \Illuminate\Support\Collection NCDR_should_mute */
    private $NCDR_should_mute;

    /**
     * MOLiBotController constructor.
     */
    public function __construct() {
        $this->NCDR_to_BOTChannel_list = collect(['地震', '土石流', '河川高水位', '降雨', '停班停課', '道路封閉', '雷雨', '颱風']); // 哪些類別的 NCDR 訊息要推到 MOLi 廣播頻道

        $this->NCDR_should_mute = collect(['土石流']); // 哪些類別的 NCDR 訊息要靜音
    }

    /**
     * 回應對 GET / 的請求
     */
    public function getIndex()
    {
        return redirect('https://moli.rocks');
    }

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

    public function getNCNU_RSS()
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('GET', 'https://www.ncnu.edu.tw/ncnuweb/ann/RSS.aspx', [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'cache-control' => 'no-cache'
                ],
                'timeout' => 10
            ]);
        } catch (GuzzleHttpTransferException $e) {
            return $e->getCode();
        }

        $fileContents = $response->getBody()->getContents();

        $formatter = Formatter::make($fileContents, Formatter::XML);

        $json = $formatter->toArray();

        return $json;
    }

    public function getNCDR_RSS()
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('GET', 'https://alerts.ncdr.nat.gov.tw/RssAtomFeeds.ashx', [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'cache-control' => 'no-cache'
                ],
                'timeout' => 10
            ]);
        } catch (GuzzleHttpTransferException $e) {
            return $e->getCode();
        }

        $fileContents = $response->getBody()->getContents();

        $formatter = Formatter::make($fileContents, Formatter::XML);

        $json = $formatter->toArray();

        return $json;
    }

    public function postNCDR(Request $request)
    {
        //use $request->getContent() to get raw data
        $formatter = Formatter::make($request->getContent(), Formatter::XML);
        $json = $formatter->toArray();

        if ( (bool)env('LOG_INPUT') ) {
            Log::info(json_encode($json, JSON_UNESCAPED_UNICODE));
        }

        if ($json['status'] == 'Actual') {
            $channel_to = env('WEATHER_CHANNEL');
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

    public function getStaffContact($keyword = NULL)
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('GET', 'http://ccweb1.ncnu.edu.tw/telquery/csvstaff2query.asp?name=' . urlencode($keyword) . '?1482238246', [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'cache-control' => 'no-cache'
                ],
                'timeout' => 10
            ]);
        } catch (GuzzleHttpTransferException $e) {
            return $e->getCode();
        }

        $fileContents = $response->getBody()->getContents();

        $array = array();

        $contents_array = str_getcsv($fileContents, "\n");

        foreach ($contents_array as $content_item) {
            $tmparray = array();
            $items = explode(",\"", $content_item);
            foreach ($items as $item) {
                array_push($tmparray, trim($item, "\"\r\n "));
            }
            array_push($array, $tmparray);
        }

        return $array;
    }

    /**
     * @return mixed
     */
    public function getFuelPrice()
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('GET', 'https://vipmember.tmtd.cpc.com.tw/OpenData/ListPriceWebService.asmx/getCPCMainProdListPrice', [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'cache-control' => 'no-cache'
                ],
                'timeout' => 10
            ]);
        } catch (GuzzleHttpTransferException $e) {
            return $e->getCode();
        }

        $fileContents = $response->getBody()->getContents();

        // SOAP response to regular XML
        $xml = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$2$3', $fileContents);

        $formatter = Formatter::make($xml, Formatter::XML);

        $json = $formatter->toArray();

        return $json['diffgrdiffgram']['NewDataSet']['tbTable'];
    }

    public function getHistoryFuelPrice(Request $request)
    {
        /*
        $prodid = array(
            '1' => '92無鉛汽油',
            '2' => '95無鉛汽油',
            '3' => '98無鉛汽油',
            '4' => '超級/高級柴油',
            '5' => '低硫燃料油(0.5%)',
            '6' => '甲種低硫燃料油(0.5)'
        );
        */

        $validator = Validator::make($request->all(), [
            'prodid' => 'required|integer|min:1|max:6',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            return response()->json(compact('messages'), 400);
        }

        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('GET', 'https://vipmember.tmtd.cpc.com.tw/OpenData/ListPriceWebService.asmx/getCPCMainProdListPrice_Historical?prodid=' . $request->input('prodid'), [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'cache-control' => 'no-cache'
                ],
                'timeout' => 10
            ]);
        } catch (GuzzleHttpTransferException $e) {
            return $e->getCode();
        }

        $fileContents = $response->getBody()->getContents();

        // SOAP response to regular XML
        $xml = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$2$3', $fileContents);

        $formatter = Formatter::make($xml, Formatter::XML);

        $json = $formatter->toArray();

        return $json['diffgrdiffgram']['NewDataSet']['tbTable'];
    }

    public function anyRoute()
    {
        return redirect('https://moli.rocks');
    }
}
