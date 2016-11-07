<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;

use MOLiBot\Http\Requests;
use MOLiBot\Http\Controllers\Controller;

use SoapBox\Formatter\Formatter;

use Log;

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
        $fileContents = curl_exec($ch);
        curl_close($ch);

        $formatter = Formatter::make($fileContents, Formatter::XML);
        $json = $formatter->toArray();
        return $json;
    }

    public function postNCDR(Request $request)
    {
        //use $request->getContent() to get raw data
        Log::info($request->getContent());
        return response('<?xml version="1.0" encoding="utf-8" ?> <Data><Status>true</Status></Data>')
            ->header('Content-Type', 'text/xml');
    }
}
