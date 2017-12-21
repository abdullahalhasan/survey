<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSmsLogHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_sms_log_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('user_mobile',255);
            $table->string('sms_contact_name',255);
            $table->string('sms_contact_number',255);
            $table->enum('sms_type', array('send', 'receive'));
            $table->string('sms_text',255);
            $table->string('sms_title',255);
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
        Schema::dropIfExists('user_sms_log_history');
    }
}
