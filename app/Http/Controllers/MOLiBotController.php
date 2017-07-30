<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;

use MOLiBot\Http\Requests;
use MOLiBot\Http\Controllers\Controller;

use SoapBox\Formatter\Formatter;
use Telegram;

use Log;

class MOLiBotController extends Controller
{
    private $NCDR_to_BOTChannel_list;

    private $NCDR_should_mute;

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

    public function getNCNU_RSS()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://www.ncnu.edu.tw/ncnuweb/ann/RSS.aspx');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["cache-control: no-cache", "user-agent: MOLi Bot"]);
        $fileContents = curl_exec($ch);
        curl_close($ch);

        $formatter = Formatter::make($fileContents, Formatter::XML);
        $json = $formatter->toArray();
        return $json;
    }

    public function postNCDR(Request $request)
    {
        //use $request->getContent() to get raw data
        $formatter = Formatter::make($request->getContent(), Formatter::XML);
        $json = $formatter->toArray();

        Log::info(json_encode($json, JSON_UNESCAPED_UNICODE));

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
                                    'text' => $info['senderName'] . '：' . $info['headline'] . PHP_EOL . $des,
                                    'disable_notification' => true
                                ];
                            } else {
                                $send_msg = [
                                    'chat_id' => $channel_to,
                                    'text' => $info['senderName'] . '：' . $info['headline'] . PHP_EOL . $des
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
                            'text' => $json['info']['senderName'] . '：' . $json['info']['headline'] . PHP_EOL . $des,
                            'disable_notification' => true
                        ];
                    } else {
                        $send_msg = [
                            'chat_id' => $channel_to,
                            'text' => $json['info']['senderName'] . '：' . $json['info']['headline'] . PHP_EOL . $des
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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://ccweb1.ncnu.edu.tw/telquery/csvstaff2query.asp?name=' . urlencode($keyword) . '?1482238246');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["cache-control: no-cache", "user-agent: MOLi Bot"]);
        $fileContents = curl_exec($ch);
        curl_close($ch);

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
    public function getFuelPrice($cmd_mode = false)
    {
        // Set unlimit excute time because of slow response from server
        ini_set('max_execution_time', 0);

        $retry_counter = 0;

        do {
            $input_xml = '<?xml version="1.0" encoding="utf-8"?><soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope"><soap12:Body><getCPCMainProdListPrice xmlns="http://tmtd.cpc.com.tw/" /></soap12:Body></soap12:Envelope>';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://vipmember.tmtd.cpc.com.tw/OpenData/ListPriceWebService.asmx');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $input_xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: text/xml',
                'cache-control: no-cache',
                'user-agent: MOLi Bot',
                'Content-Type: application/soap+xml;charset=utf-8',
                'Content-length: '.strlen($input_xml),
            ]);

            $fileContents = curl_exec($ch);

            curl_close($ch);

            if (!$cmd_mode) {
                $retry_counter++;
            }
        } while (empty($fileContents) && $retry_counter <= 5);

        if ($retry_counter >= 5) {
            return response()->json(['messages' => 'request take too long!'], 408);
        }

        // SOAP response to regular XML
        $xml = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$2$3', $fileContents);

        $formatter = Formatter::make($xml, Formatter::XML);

        $json = $formatter->toArray();

        return $json['soapBody']['getCPCMainProdListPriceResponse']['getCPCMainProdListPriceResult']['diffgrdiffgram']['NewDataSet']['tbTable'];
    }

    public function getHistoryFuelPrice($cmd_mode = false)
    {
        // Set unlimit excute time because of slow response from server
        ini_set('max_execution_time', 0);

        $types = array(
            '1' => '92無鉛汽油',
            '2' => '95無鉛汽油',
            '3' => '98無鉛汽油',
            '4' => '超級/高級柴油',
            '5' => '低硫燃料油(0.5%)',
            '6' => '甲種低硫燃料油(0.5)'
        );

        $result = array();

        foreach ($types as $key => $type) {
            $retry_counter = 0;

            do {
                $input_xml = '<?xml version="1.0" encoding="utf-8"?>
                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                    <soap12:Body>
                        <getCPCMainProdListPrice_Historical xmlns="http://tmtd.cpc.com.tw/">
                            <prodid>'. (string)$key .'</prodid>
                        </getCPCMainProdListPrice_Historical>
                    </soap12:Body>
                </soap12:Envelope>';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://vipmember.tmtd.cpc.com.tw/OpenData/ListPriceWebService.asmx');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $input_xml);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Accept: text/xml',
                    'cache-control: no-cache',
                    'user-agent: MOLi Bot',
                    'Content-Type: application/soap+xml;charset=utf-8',
                    'Content-length: ' . strlen($input_xml),
                ]);

                $fileContents = curl_exec($ch);

                curl_close($ch);

                $retry_counter++;
            } while (empty($fileContents) && $retry_counter <= 5);

            if ($retry_counter >= 5) {
                return response()->json(['messages' => 'request take too long!'], 408);
            }

            // SOAP response to regular XML
            $xml = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$2$3', $fileContents);

            $formatter = Formatter::make($xml, Formatter::XML);

            $json = $formatter->toArray();

            $result += array($type => $json['soapBody']['getCPCMainProdListPrice_HistoricalResponse']['getCPCMainProdListPrice_HistoricalResult']['diffgrdiffgram']['NewDataSet']['tbTable']);
        }

        return response()->json($result);
    }

    private function ncdr_publish($info) {

    }
}
