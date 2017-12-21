<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{

    /**
     * Class constructor.
     * get current route name for page title.
     *
     * data write to access_log table.
     */
    public function __construct()
    {
        $this->page_title = \Request::route()->getName();
        //\App\System::AccessLogWrite();
    }

    public function create($campaign_id,$page_number)
    {
        $cam_name = \DB::table('survey_campaign')
            ->where('id',$campaign_id)
            ->first();
        $questions = \DB::table('survey_question')
            ->leftjoin('question_input_type','survey_question.question_input_type_id' ,'=' ,'question_input_type.id')
            ->select('survey_question.*','question_input_type.input_type_name','question_input_type.input_type_value')
            ->where('survey_question.question_page_no',$page_number)
            ->where('survey_question.campaign_id',$campaign_id)
            ->orderBy('survey_question.id','DESC')
            ->get();
        $data['campaign_id'] = $campaign_id;
        $data['page_number'] = $page_number;
        $data['questions'] = $questions;
        $data['cam_name'] = $cam_name;
        $data['page_title'] = $this->page_title;
        return view('answer.create',$data);
    }

    public function store(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'campaign_id' => 'required',
            'question_id' => 'required'
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $page_number = $request->input('page_number');
        $question_id = $request->input('question_id');
        $in_page_number = $page_number+1;
        $campaign_id = $request->input('campaign_id');
        $page_numbers = Question::where('campaign_id','=', $campaign_id)->pluck('question_page_no')->toArray();
        $question = \DB::table('survey_question')
            ->where('id', $question_id)
            ->first();
        $inputTypeIds = array('10','11');
        $latestAnswers = \DB::table('question_answer')
            ->where('answer_user_id',\Auth::user()->id)
            ->where('campaign_id',$campaign_id)
            ->where('answer_question_id',$question_id)
            ->orderBy('created_at', 'desc')
            ->get();
        try {
            if($question->branching_enable == 1) {
                $branchOption = \DB::table('branching_question_condition')
                    ->where('b_question_id',$question->id)
                    ->get();

            } else {
                if (!empty($request->input('answer_option_group_value'))
                    && is_array($request->input('answer_option_group_value'))){
                    foreach($_POST['answer_option_group_value'] as $key => $val) {
                        $option_group = \DB::table('question_option_group')
                            ->leftjoin('survey_question','question_option_group.option_question_id','=','survey_question.id')
                            ->select('question_option_group.*','survey_question.question_title')
                            ->where('question_option_group.id',$val)
                            ->first();
                        if(in_array($option_group->option_input_type_id, $inputTypeIds)) {
                            $value = $request->input('user_answer');
                            $answer_option_group_value = $value[$key];
                            $answer_option_group_name = $option_group->question_option_name;
                        } else {
                            if ( $option_group->question_option_name == '') {
                                $name = $request->input('user_answer');
                                $answer_option_group_name = $name[$key];
                            } else {
                                $answer_option_group_name = $option_group->question_option_name;
                            }
                            if($option_group->question_option_value == '') {
                                $value = $request->input('user_answer');
                                $answer_option_group_value = $value[$key];
                            } else {
                                $answer_option_group_value = $option_group->question_option_value;
                            }
                        }
                        $question_answer = array(
                            'answer_user_id' => \Auth::user()->id,
                            'campaign_id' => $campaign_id,
                            'answer_question_id' => $option_group->option_question_id,
                            'answer_question_title' =>$option_group->question_title,
                            'answer_input_type_id' => $option_group->option_input_type_id,
                            'answer_option_group_id' => $option_group->id,
                            'answer_option_group_name' => $answer_option_group_name,
                            'answer_option_group_value' => $answer_option_group_value,
                            'created_at' =>$now,
                            'updated_at' =>$now,
                            'created_by' =>\Auth::user()->id,
                            'updated_by' =>\Auth::user()->id,
                        );
                        \DB::table('question_answer')->insert($question_answer);
                        //var_dump($question_answer);
                        $key = $key +1;
                    }
                    if (in_array($in_page_number, $page_numbers)) {
                        $meta_mask = \DB::table('question_meta')
                            ->where('meta_question_field_name','masked_question_id')
                            ->where('meta_question_field_value',$question_id)
                            ->where('campaign_id',$campaign_id)
                            ->first();
                        $meta_re_mask = \DB::table('question_meta')
                            ->where('meta_question_field_name','re_masked_question_id')
                            ->where('meta_question_field_value',$question_id)
                            ->where('campaign_id',$campaign_id)
                            ->first();
                        if ($meta_mask) {
                            $next_mask_question = \DB::table('survey_question')
                                ->where('id',$meta_mask->question_id)
                                ->first();
                            if($next_mask_question->masking_enable == 1) {
                                $count = array(
                                    'user_id' => \Auth::user()->id,
                                    'type' => 'mask',
                                    'total_option' => $key,
                                    'created_at' =>$now,
                                    'updated_at' =>$now,
                                    'created_by' =>\Auth::user()->id,
                                    'updated_by' =>\Auth::user()->id,
                                );
                                \DB::table('count_mask_rmask')->insert($count);
                            }

                        }
                        if ($meta_re_mask) {
                            $next_re_mask_question = \DB::table('survey_question')
                                ->where('id',$meta_re_mask->question_id)
                                ->first();
                            if($next_re_mask_question->remasking_enable == 1) {
                                $count = array(
                                    'user_id' => \Auth::user()->id,
                                    'type' => 'rmask',
                                    'total_option' => $key,
                                    'created_at' =>$now,
                                    'updated_at' =>$now,
                                    'created_by' =>\Auth::user()->id,
                                    'updated_by' =>\Auth::user()->id,
                                );
                                \DB::table('count_mask_rmask')->insert($count);
                            }
                        }
                        return \Redirect::to('admin/question/answer/create/'.$campaign_id.'/'.$in_page_number)
                            ->with('message',"Welcome to next page");
                    } else {
                        $campaign_information = \DB::table('survey_campaign')
                            ->where('id',$campaign_id)
                            ->first();
                        $activity = array(
                            'user_id' => \Auth::user()->id,
                            'campaign_id' => $campaign_information->id,
                            'activity_date' => $now,
                            'campaign_incentive_amount' => $campaign_information->campaign_incentive_amount,
                            'campaign_incentive_point' => $campaign_information->campaign_incentive_point,
                            'activity_description' => $campaign_information->campaign_title,
                            'created_at' =>$now,
                            'updated_at' =>$now,
                            'created_by' =>\Auth::user()->id,
                            'updated_by' =>\Auth::user()->id,
                        );
                        \DB::table('user_activity')->insert($activity);
                        return \Redirect::to('admin/survey/campaign')->with('message',"Question end.Thanks for your answer");
                    }
                } else {
                    return redirect()->back()->with('errormessage','Please give answer at least one question');
                }
            }
        } catch (\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage',$message);
        }
    }

    public function checkMinMax($id, $value)
    {
        $option = \DB::table('question_option_group')
            ->where('id',$id)
            ->first();
        if(count($option) > 0) {
            if(isset($option->max)) {
                if($option->max < $value) {
                    return response()->json(['status' => 'max']);
                }
            }
            if(isset($option->min)) {
                if($option->min > $value) {
                    return response()->json(['status' => 'min']);
                }
            }
            return response()->json(['option' => '##']);
        } else {
            return response()->json(['status' => "notFound"]);
        }
    }
}
