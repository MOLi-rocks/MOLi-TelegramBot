<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\FuelPriceRepository;
use MOLiBot\DataSources\CPCProductPrice as DataSource;

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
     * @return array
     * @throws
     */
    public function calculateGap()
    {
        $result = [];

        $priceContents = $this->dataSource->getContent();

        foreach ($priceContents as $data) {
            $lastRecord = $this->fuelPriceRepository->getNewestRecordByName($data['產品名稱']);

            if (!empty($lastRecord) && $lastRecord->start_at === $data['牌價生效時間']) {
                $result += [
                    $data['產品名稱'] => ' 將 不調整 (' . (float)$data['參考牌價'] . ')'
                ];
            } else {
                $lastPrice = $lastRecord->price ?? 0;

                $priceGapCal = bcsub((string)$data['參考牌價'], (string)$lastPrice, 1);

                $priceGap = floatval($priceGapCal);

                if ($priceGap > 0) {
                    $result += [
                        $data['產品名稱'] => ' 將 調漲 ' . $priceGap . ' ' . $data['計價單位'] .
                            ' (' . (float)$lastPrice . ' &#8594; ' . (float)$data['參考牌價'] . ')'
                    ];
                } else if ($priceGap < 0) {
                    $result += [
                        $data['產品名稱'] => ' 將 調降 ' . abs($priceGap) . ' ' . $data['計價單位'] .
                            ' (' . (float)$lastPrice . ' &#8594; ' . (float)$data['參考牌價'] . ')'
                    ];
                } else {
                    $result += [
                        $data['產品名稱'] => ' 將 不調整 (' . (float)$data['參考牌價'] . ')'
                    ];
                }

                $this->fuelPriceRepository->createPriceRecord($data);
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