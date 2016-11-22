<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;

use MOLiBot\Http\Requests;
use MOLiBot\Http\Controllers\Controller;

use SoapBox\Formatter\Formatter;
use Telegram;

class MOLiBotController extends Controller
{
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

        if (!isset($json['info']['description'])) {
            foreach ($json['info'] as $info) {
                Telegram::sendMessage([
                    'chat_id' => env('TEST_CHANNEL'),
                    'text' => $info['description'],
                ]);
            }
        } else {
            Telegram::sendMessage([
                'chat_id' => env('TEST_CHANNEL'),
                'text' => $json['info']['description'],
            ]);
        }

        return response('<?xml version="1.0" encoding="UTF-8" ?><Data><Status>true</Status></Data>')
            ->header('Content-Type', 'text/xml');
    }
}
