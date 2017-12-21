<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSurveyHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_survey_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('survey_campaign_id');
            $table->dateTime('survey_completed_date_time');
            $table->decimal('user_incentive_amount',10,2);
            $table->decimal('user_incentive_point',10,2);
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
        Schema::dropIfExists('user_survey_history');
    }
}
