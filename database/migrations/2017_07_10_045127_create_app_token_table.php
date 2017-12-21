<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_token', function (Blueprint $table) {
            $table->increments('token_ref_id');
            $table->string('imei_no');
            $table->string('app_key');
            $table->string('access_token');
            $table->string('client_ip');
            $table->string('access_browser');
            $table->string('access_city');
            $table->string('access_division');
            $table->string('access_country');
            $table->string('referenceCode');
            $table->string('token_status');
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
        Schema::dropIfExists('app_token');
    }
}
