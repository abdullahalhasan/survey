<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_answer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('answer_user_id');
            $table->integer('answer_question_id');
            $table->string('answer_question_title',255);
            $table->integer('answer_input_type_id');
            $table->integer('answer_option_group_id');
            $table->string('answer_option_group_name');
            $table->string('answer_option_group_value');
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
        Schema::dropIfExists('question_answer');
    }
}
