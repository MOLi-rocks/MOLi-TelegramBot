<?php

namespace MOLiBot\Services;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;

class NcnuStaffContactService
{
    public function __construct()
    {
        //
    }

    public function getStaffContact($keyword = NULL)
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request(
                'GET',
                'http://ccweb1.ncnu.edu.tw/telquery/csvstaff2query.asp?name=' . urlencode($keyword) . '?' . time(),
                [
                    'headers' => [
                        'User-Agent' => 'MOLi Bot',
                        'Accept-Encoding' => 'gzip',
                        'cache-control' => 'no-cache'
                    ],
                    'timeout' => 10
                ]
            );
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
}