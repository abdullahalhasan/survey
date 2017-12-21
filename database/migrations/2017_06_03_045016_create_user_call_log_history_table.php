<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCallLogHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_call_log_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('user_mobile',255);
            $table->string('contact_name',255);
            $table->string('contact_number',255);
            $table->enum('call_type', array('incoming', 'outgoing'));
            $table->time('call_time');
            $table->string('call_duration');
            $table->string('created_by');
            $table->string('updated_by');
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
        Schema::dropIfExists('user_call_log_history');
    }
}
