<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_log', function (Blueprint $table) {
            $table->uuid('access_tran_code')->primary();
            $table->string('access_client_ip');
            $table->string('access_user_id');
            $table->string('access_browser');
            $table->string('access_platform');
            $table->string('access_city');
            $table->string('access_division');
            $table->string('access_country');
            $table->string('access_message');
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
        Schema::drop('access_log');
    }
}
