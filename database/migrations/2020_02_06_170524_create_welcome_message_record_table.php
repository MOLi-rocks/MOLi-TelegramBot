<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWelcomeMessageRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welcome_message_record', function (Blueprint $table) {
            $table->bigInteger('chat_id');
            $table->bigInteger('member_id')->comment('new member id');
            $table->bigInteger('welcome_message_id');
            $table->boolean('checked')->default(false);
            $table->bigInteger('join_at')->comment('unix-timestamp of join time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welcome_message_record');
    }
}
