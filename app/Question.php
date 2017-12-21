<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    public $table = "survey_question";
    protected $fillable = [
        'question_title', 'question_help_text'
    ];
    public static function getAllSurveyQuestion()
    {
        $questions = \DB::table('survey_question')
            ->leftjoin('survey_campaign','survey_question.campaign_id', '=','survey_campaign.id')
            ->leftjoin('question_input_type','survey_question.question_input_type_id','=','question_input_type.id')
            ->select('survey_question.*','survey_campaign.campaign_title','question_input_type.input_type_name')
            ->orderBy('survey_question.id','DESC')
            ->paginate(10);
        return $questions;
    }
    public static function getSurveyQuestionById($question_id)
    {
        $question = \DB::table('survey_question')
            ->leftjoin('survey_campaign','survey_question.campaign_id', '=','survey_campaign.id')
            ->leftjoin('question_input_type','survey_question.question_input_type_id','=','question_input_type.id')
            ->select('survey_question.*','survey_campaign.campaign_title','question_input_type.input_type_name')
            ->where('survey_question.id',$question_id)
            ->first();
        return $question;
    }
    public static function getAllSurveyQuestionByPageNumber($page_number,$campaign_id)
    {
        $questions = \DB::table('survey_question')
            ->leftjoin('question_input_type','survey_question.question_input_type_id' ,'=' ,'question_input_type.id')
            ->select('survey_question.*','question_input_type.input_type_name','question_input_type.input_type_value')
            ->where('survey_question.question_page_no',$page_number)
            ->where('survey_question.campaign_id',$campaign_id)
            ->orderBy('survey_question.id','DESC')
            ->get();
        return $questions;
    }

    public static function checkBranchingRation($question_id)
    {
        if($question_id == "answered") {
            return "answered";
        }
        if($question_id == "not_answered") {
            return "not_answered";
        }
        if($question_id == "is_equal") {
            return "is_equal";
        }
        if($question_id == "is_equal") {
            return "is_equal";
        }
        if($question_id == "is_less_than") {
            return "is_less_than";
        }
        if($question_id == "is_greater_than") {
            return "is_greater_than";
        }
        if($question_id == "is_less_than_equal") {
            return "is_less_than_equal";
        }
        if($question_id == "is_greater_than_equal") {
            return "is_greater_than_equal";
        }

    }

    /**
     * @param $condition
     * @param $val_1
     * @param $val_2
     * @return bool
     */
    public static function ConditionCheck($condition,$val_1,$val_2){
        if ($condition == '' || $condition == 'and') {
            if($val_1 && $val_2) {
                return true;
            } else {
                return false;
            }
        }
        if($condition == 'or'){
            if($val_1 || $val_2) {
                return true;
            } else {
                return false;
            }
        }

    }

}



