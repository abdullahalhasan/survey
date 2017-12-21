<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('name_slug')->after('name');
            $table->string('user_type')->after('name_slug');
            $table->string('user_role')->after('user_type');
            $table->string('user_mobile')->after('password');
            $table->string('user_profile_image')->after('user_mobile');
            $table->integer('login_status')->after('user_profile_image');
            $table->string('status')->after('login_status');
            $table->timestamp('last_login')->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
