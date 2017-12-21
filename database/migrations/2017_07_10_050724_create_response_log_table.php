<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponseLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('response_log', function (Blueprint $table) {
            $table->increments('response_id');
            $table->string('response_client_ip');
            $table->string('response_user_id');
            $table->string('response_request_url');
            $table->string('response_type');
            $table->longText('response_data');
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
        Schema::dropIfExists('response_log');
    }
}
