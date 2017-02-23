<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;

use MOLiBot\Http\Requests;
use MOLiBot\Http\Controllers\Controller;

use SoapBox\Formatter\Formatter;
use Telegram;

class MOLiBotController extends Controller
{
    private $NCDR_to_BOTChannel_list;

    public function __construct() {
        $this->NCDR_to_BOTChannel_list = array('地震'); // 哪些類別的 NCDR 訊息要推到 MOLi 廣播頻道
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

        if ($json['status'] == 'Actual') {
            $channelto = env('TEST_CHANNEL');

            if (!isset($json['info']['description'])) {
                foreach ($json['info'] as $info) {
                    foreach ($this->NCDR_to_BOTChannel_list as $to_BOTChannel_item) {
                        if ($to_BOTChannel_item == $info['event']) {
                            $channelto = env('MOLi_CHANNEL');
                            break;
                        }
                    }

                    Telegram::sendMessage([
                        'chat_id' => $channelto,
                        'text' => $info['description'],
                    ]);
                }
            } else {
                foreach ($this->NCDR_to_BOTChannel_list as $to_BOTChannel_item) {
                    if ($to_BOTChannel_item == $info['event']) {
                        $channelto = env('MOLi_CHANNEL');
                        break;
                    }
                }

                Telegram::sendMessage([
                    'chat_id' => $channelto,
                    'text' => $json['info']['description'],
                ]);
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
}
