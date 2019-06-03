<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\Models\FuelPrice
 *
 * @property string $name
 * @property string $unit
 * @property float $price
 * @property string $start_at
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice whereUnit($value)
 * @mixin \Eloquent
 */
class FuelPrice extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'history_fuel_price';

    protected $fillable = ['name', 'unit', 'price', 'start_at'];
}
