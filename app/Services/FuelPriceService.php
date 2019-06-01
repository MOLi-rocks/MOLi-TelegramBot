<?php

namespace MOLiBot\Services;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;
use MOLiBot\Repositories\FuelPriceRepository;
use SoapBox\Formatter\Formatter;

class FuelPriceService
{
    private $fuelPriceRepository;

    public function __construct(FuelPriceRepository $fuelPriceRepository)
    {
        $this->fuelPriceRepository = $fuelPriceRepository;
    }

    public function getLiveFuelPrice()
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request(
                'GET',
                'https://vipmember.tmtd.cpc.com.tw/OpenData/ListPriceWebService.asmx/getCPCMainProdListPrice', [
                    'headers' => [
                        'User-Agent' => 'MOLi Bot',
                        'cache-control' => 'no-cache'
                    ],
                    'timeout' => 10
                ]
            );
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

    public function calculateGap($liveDatas)
    {
        $result = [];

        foreach ($liveDatas as $data) {
            if ( $this->fuelPriceRepository->checkRecordExistByNameStartTime($data['產品名稱'], $data['牌價生效時間']) ) {
                $result += [
                    $data['產品名稱'] => ' 將 不調整 (' . $data['參考牌價'] . ')'
                ];
            } else {
                $lasttime = $this->fuelPriceRepository->getNewestRecordByName($data['產品名稱']);

                if ($lasttime) {
                    $lasttimeprice = $lasttime->price;
                } else {
                    $lasttimeprice = '0';
                }

                $this->fuelPriceRepository->createPriceRecord($data);

                $pricegap_cal = bcsub($data['參考牌價'], $lasttimeprice, 1);

                $pricegap = floatval($pricegap_cal);

                if ($pricegap > 0) {
                    $result += [
                        $data['產品名稱'] => ' 將 調漲 ' . $pricegap . ' ' . $data['計價單位'] . ' (' . (float)$lasttimeprice . ' &#8594; ' . $data['參考牌價'] . ')'
                    ];
                } else if ($pricegap < 0) {
                    $result += [
                        $data['產品名稱'] => ' 將 調降 ' . abs($pricegap) . ' ' . $data['計價單位'] . ' (' . (float)$lasttimeprice . ' &#8594; ' . $data['參考牌價'] . ')'
                    ];
                } else {
                    $result += [
                        $data['產品名稱'] => ' 將 不調整 (' . $data['參考牌價'] . ')'
                    ];
                }
            }
        }

        return $result;
    }

    public function getHistoryFuelPrice($prodId)
    {
        /*
        $prodId = array(
            '1' => '92無鉛汽油',
            '2' => '95無鉛汽油',
            '3' => '98無鉛汽油',
            '4' => '超級/高級柴油',
            '5' => '低硫燃料油(0.5%)',
            '6' => '甲種低硫燃料油(0.5)'
        );
        */

        $client = new GuzzleHttpClient();

        try {
            $response = $client->request(
                'GET',
                'https://vipmember.tmtd.cpc.com.tw/OpenData/ListPriceWebService.asmx/getCPCMainProdListPrice_Historical?prodid=' . $prodId, [
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
}