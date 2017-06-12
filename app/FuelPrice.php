<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\FuelPrice
 *
 * @property string $name
 * @property string $unit
 * @property float $price
 * @property string $start_at
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\FuelPrice whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\FuelPrice wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\FuelPrice whereStartAt($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\FuelPrice whereUnit($value)
 * @mixin \Eloquent
 */
class FuelPrice extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'history_fuel_price';

    protected $fillable = ['name', 'unit', 'price', 'start_at'];
}
