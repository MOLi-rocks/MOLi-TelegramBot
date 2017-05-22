<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryFuelPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_fuel_price', function (Blueprint $table) {
            $table->string('name');
            $table->string('unit');
            $table->decimal('price', 8, 3);
            $table->dateTimeTz('start_at');
            $table->primary(['name', 'start_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_fuel_price');
    }
}
