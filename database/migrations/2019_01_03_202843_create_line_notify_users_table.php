<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLINENotifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_notify_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_token');
            $table->string('targetType')->nullable();
            $table->string('target')->nullable();
            $table->string('sid')->nullable(); // 學號，保留用來辨識
            $table->string('email')->nullable(); // Email，保留用來辨識
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_notify_users');
    }
}
