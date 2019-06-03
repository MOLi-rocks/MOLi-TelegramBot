<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\FuelPrice;

class FuelPriceRepository
{
    private $fuelPriceModel;

    /**
     * FuelPriceRepository constructor.
     * @param FuelPrice $fuelPriceModel
     */
    public function __construct(FuelPrice $fuelPriceModel)
    {
        $this->fuelPriceModel = $fuelPriceModel;
    }

    public function createPriceRecord($data)
    {
        return $this->fuelPriceModel->create([
            'name' => $data['產品名稱'],
            'unit' => $data['計價單位'],
            'price' => $data['參考牌價'],
            'start_at' => $data['牌價生效時間']
        ]);
    }

    public function getNewestRecordByName($productName)
    {
        return $this->fuelPriceModel->where('name', '=', $productName)
            ->orderBy('start_at', 'desc')
            ->first();
    }

    public function checkRecordExistByNameStartTime($productName, $startAt)
    {
        return $this->fuelPriceModel->where('name', '=', $productName)
            ->where('start_at', '=', $startAt)
            ->exists();
    }
}