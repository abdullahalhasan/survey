<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->string('question_title',255);
            $table->string('question_help_text');
            $table->boolean('question_answer_require');
            $table->string('question_input_type_id');
            $table->string('question_page_no');
            $table->boolean('masking_enable');
            $table->boolean('branching_enable');
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
        Schema::dropIfExists('survey_question');
    }
}
