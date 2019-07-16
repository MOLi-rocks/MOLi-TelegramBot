<?php

namespace MOLiBot\Services;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;
use MOLiBot\Repositories\FuelPriceRepository;
use MOLiBot\DataSources\CPCProductPrice as DataSource;
use SoapBox\Formatter\Formatter;

class FuelPriceService
{
    private $fuelPriceRepository;
    private $dataSource;

    public function __construct(FuelPriceRepository $fuelPriceRepository)
    {
        $this->fuelPriceRepository = $fuelPriceRepository;
        $this->dataSource = new DataSource();
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLiveFuelPrice()
    {
        return $this->dataSource->getContent();
    }

    /**
     * @param $liveDatas
     * @return array
     */
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

    /**
     * @param $prodId
     * @return int|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHistoryFuelPrice($prodId)
    {
        $this->dataSource->setHistoryProdId($prodId);

        return $this->dataSource->getHistoryContent();
    }
}