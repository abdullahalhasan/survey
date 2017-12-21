<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_log', function (Blueprint $table) {
            $table->uuid('auth_tran_code')->primary();
            $table->string('auth_client_ip');
            $table->string('auth_user_id');
            $table->string('auth_browser');
            $table->string('auth_platform');
            $table->string('auth_city');
            $table->string('auth_division');
            $table->string('auth_country');
            $table->string('auth_type');
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
        Schema::drop('auth_log');
    }
}
