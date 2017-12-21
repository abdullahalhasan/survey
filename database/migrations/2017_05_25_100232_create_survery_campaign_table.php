<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveryCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_campaign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_category_id')->unsigned();
            $table->string('campaign_name',255);
            $table->string('campaign_title',255);
            $table->string('campaign_owner',255);
            $table->date('active_date');
            $table->date('expire_date');
            $table->decimal('campaign_incentive_amount',10,2);
            $table->decimal('campaign_incentive_point',10,2);
            $table->string('campaign_instructions',255);
            $table->string('campaign_ending_text',255);
            $table->boolean('status')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
        });
        Schema::table('survey_campaign', function($table) {
            $table->foreign('campaign_category_id')->references('id')->on('campaign_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_campaign');
    }
}
