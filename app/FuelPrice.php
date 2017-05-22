<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;

class FuelPrice extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'history_fuel_price';

    protected $fillable = ['name', 'unit', 'price', 'start_at'];
}
