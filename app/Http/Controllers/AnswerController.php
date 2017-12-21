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

    public function create($campaign_id,$page_number,$user_id)
    {
        $now = date('Y-m-d H:i:s');
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
        $questionInput = \DB::table('survey_question')
            ->where('question_page_no',$page_number)
            ->where('campaign_id',$campaign_id)
            ->first();
        if(count($questions) > 0) {
            foreach ($questions as $question) {
                if($question->branching_enable == 1) {
                    $branchingQuestion = \DB::table('branching_question_condition')
                        ->where('b_question_id',$question->id)
                        ->get();
                    if(!empty($branchingQuestion) && count($branchingQuestion) > 0) {
                        $allData = array();
                        foreach ($branchingQuestion as $branch) {
                            if($branch->relation_symbol == "answered") {
                                $latestAnswer = \DB::table('question_answer')
                                    ->where('answer_user_id',$user_id)
                                    ->where('campaign_id',$campaign_id)
                                    ->where('is_completed',0)
                                    ->where('answer_question_id',$branch->reference_question_id)
                                    ->where('answer_option_group_value',$branch->r_option_value)
                                    ->where('answer_option_group_name',$branch->r_option_name)
                                    ->first();
                                if(count($latestAnswer) > 0) {
                                    $branch_condition = true;
                                } else {
                                    $branch_condition = false;
                                }
                            }
                            if($branch->relation_symbol == "not_answered") {
                                $latestAnswer = \DB::table('question_answer')
                                    ->where('answer_user_id',$user_id)
                                    ->where('campaign_id',$campaign_id)
                                    ->where('is_completed',0)
                                    ->where('answer_question_id',$branch->reference_question_id)
                                    ->where('answer_option_group_value',$branch->r_option_value)
                                    ->where('answer_option_group_name',$branch->r_option_name)
                                    ->first();
                                if(count($latestAnswer) > 0) {
                                    $branch_condition = true;
                                } else {
                                    $branch_condition = false;
                                }
                            }

                            if(($branch->relation_symbol == "is_equal")) {
                                $latestAnswer = \DB::table('question_answer')
                                    ->where('answer_user_id',$user_id)
                                    ->where('campaign_id',$campaign_id)
                                    ->where('is_completed',0)
                                    ->where('answer_question_id',$branch->reference_question_id)
                                    ->where('answer_option_group_value',$branch->r_option_value)
                                    ->where('answer_option_group_name',$branch->r_option_name)
                                    ->first();
                                if(count($latestAnswer) > 0) {
                                    if($latestAnswer->answer_option_group_value == $branch->compare_value ) {
                                        $branch_condition = true;
                                    } else {
                                        $branch_condition = false;
                                    }
                                } else {
                                    $branch_condition = false;
                                }
                            }
                            if($branch->relation_symbol == "is_less_than") {
                                $latestAnswer = \DB::table('question_answer')
                                    ->where('answer_user_id',$user_id)
                                    ->where('campaign_id',$campaign_id)
                                    ->where('is_completed',0)
                                    ->where('answer_question_id',$branch->reference_question_id)
                                    ->where('answer_option_group_value',$branch->r_option_value)
                                    ->where('answer_option_group_name',$branch->r_option_name)
                                    ->first();
                                if(count($latestAnswer) > 0) {
                                    if($latestAnswer->answer_option_group_value < $branch->compare_value ) {
                                        $branch_condition = true;
                                    } else {
                                        $branch_condition = false;
                                    }
                                } else {
                                    $branch_condition = false;
                                }
                            }
                            if($branch->relation_symbol == "is_greater_than") {
                                $latestAnswer = \DB::table('question_answer')
                                    ->where('answer_user_id',$user_id)
                                    ->where('campaign_id',$campaign_id)
                                    ->where('is_completed',0)
                                    ->where('answer_question_id',$branch->reference_question_id)
                                    ->where('answer_option_group_value',$branch->r_option_value)
                                    ->where('answer_option_group_name',$branch->r_option_name)
                                    ->first();
                                if(count($latestAnswer) > 0) {
                                    if(count($latestAnswer) > 0) {
                                        if($latestAnswer->answer_option_group_value > $branch->compare_value ) {
                                            $branch_condition = true;
                                        } else {
                                            $branch_condition = false;
                                        }
                                    } else {
                                        $branch_condition = false;
                                    }
                                } else {
                                    $branch_condition = false;
                                }
                            }
                            if($branch->relation_symbol == "is_less_than_equal") {
                                $latestAnswer = \DB::table('question_answer')
                                    ->where('answer_user_id',$user_id)
                                    ->where('campaign_id',$campaign_id)
                                    ->where('is_completed',0)
                                    ->where('answer_question_id',$branch->reference_question_id)
                                    ->where('answer_option_group_value',$branch->r_option_value)
                                    ->where('answer_option_group_name',$branch->r_option_name)
                                    ->first();
                                if(count($latestAnswer) > 0) {
                                    if($latestAnswer->answer_option_group_value <= $branch->compare_value ) {
                                        $branch_condition = true;
                                    } else {
                                        $branch_condition = false;
                                    }
                                } else {
                                    $branch_condition = false;
                                }
                            }
                            if($branch->relation_symbol == "is_greater_than_equal") {
                                $latestAnswer = \DB::table('question_answer')
                                    ->where('answer_user_id',$user_id)
                                    ->where('campaign_id',$campaign_id)
                                    ->where('is_completed',0)
                                    ->where('answer_question_id',$branch->reference_question_id)
                                    ->where('answer_option_group_value',$branch->r_option_value)
                                    ->where('answer_option_group_name',$branch->r_option_name)
                                    ->first();
                                if(count($latestAnswer) > 0) {
                                    if(count($latestAnswer) > 0) {
                                        if($latestAnswer->answer_option_group_value >= $branch->compare_value ) {
                                            $branch_condition = true;
                                        } else {
                                            $branch_condition = false;
                                        }
                                    } else {
                                        $branch_condition = false;
                                    }
                                } else {
                                    $branch_condition = false;
                                }
                            }
                            $data = array(
                                'id' => $branch->id,
                                'term' => $branch->term,
                                'condition_result' => $branch_condition
                            );
                            $allData []=  $data;
                        }
                        //$result = true;
                        $last_result=null;
                        $len = count($allData);
                        if($len == 1) {
                            $last_result = \App\Question::ConditionCheck($allData[$len-1]['term'],$allData[$len-1]['condition_result'],$allData[$len-1]['condition_result']);
                        } else {
                            for($i=0; $i<count($allData)-1;$i++) {
                                if ($i==0){
                                    $last_result = \App\Question::ConditionCheck($allData[$i+1]['term'],$allData[$i]['condition_result'],$allData[$i+1]['condition_result']);
                                }
                                $last_result = \App\Question::ConditionCheck($allData[$i+1]['term'],$last_result,$allData[$i+1]['condition_result']);
                            }
                        }
                        if($last_result) {
                            $data['user_id'] = $user_id;
                            $data['questions'] = $questions;
                            $data['campaign_id'] = $campaign_id;
                            $data['page_number'] = $page_number;
                            $data['cam_name'] = $cam_name;
                            $data['questionInput'] = $questionInput;
                            $data['page_title'] = $this->page_title;
                            return view('answer.create',$data);
                        } else {
                            $questions = \DB::table('survey_question')
                                ->leftjoin('question_input_type','survey_question.question_input_type_id' ,'=' ,'question_input_type.id')
                                ->select('survey_question.*','question_input_type.input_type_name','question_input_type.input_type_value')
                                ->where('survey_question.question_page_no',$page_number+2)
                                ->where('survey_question.campaign_id',$campaign_id)
                                ->orderBy('survey_question.id','DESC')
                                ->get();
                            if(!empty($questions) && count($questions) > 0) {
                                $data['user_id'] = $user_id;
                                $data['questions'] = $questions;
                                $data['campaign_id'] = $campaign_id;
                                $data['page_number'] = $page_number;
                                $data['cam_name'] = $cam_name;
                                $data['questionInput'] = $questionInput;
                                $data['page_title'] = $this->page_title;
                                return view('answer.create',$data);
                            } else {
                                $lastQuestion = \DB::table('survey_question')
                                    ->where('campaign_id',$campaign_id)
                                    ->where('question_input_type_id','14')
                                    ->first();
                                $activity = array(
                                    'user_id' => $user_id,
                                    'campaign_id' => $cam_name->id,
                                    'activity_date' => $now,
                                    'campaign_incentive_amount' => $cam_name->campaign_incentive_amount,
                                    'campaign_incentive_point' => $cam_name->campaign_incentive_point,
                                    'activity_description' => $cam_name->campaign_title,
                                    'created_at' =>$now,
                                    'updated_at' =>$now,
                                    'created_by' => $user_id,
                                    'updated_by' => $user_id
                                );
                                $user_activity = \DB::table('user_activity')->insert($activity);
                                if($user_activity){
                                    \DB::table('question_answer')
                                        ->where('answer_user_id',$user_id)
                                        ->where('campaign_id',$campaign_id)
                                        ->where('is_completed',0)
                                        ->update(array(
                                            'is_completed' => 1
                                        ));
                                }
                                return \Redirect::to('admin/question/answer/create/'.$campaign_id.'/'.$lastQuestion->question_page_no.'/'.$user_id)
                                    ->with('message',"Welcome to next page");
                                //return \Redirect::to('admin/survey/campaign')->with('message',"Question end.Thanks for your answer");
                            }
                        }
                    } else {
                        $questions = \DB::table('survey_question')
                            ->leftjoin('question_input_type','survey_question.question_input_type_id' ,'=' ,'question_input_type.id')
                            ->select('survey_question.*','question_input_type.input_type_name','question_input_type.input_type_value')
                            ->where('survey_question.question_page_no',$page_number+2)
                            ->where('survey_question.campaign_id',$campaign_id)
                            ->orderBy('survey_question.id','DESC')
                            ->get();
                        if(!empty($questions) && count($questions) > 0) {
                            $data['questions'] = $questions;
                            $data['campaign_id'] = $campaign_id;
                            $data['page_number'] = $page_number;
                            $data['cam_name'] = $cam_name;
                            $data['questionInput'] = $questionInput;
                            $data['page_title'] = $this->page_title;
                            $data['user_id'] = $user_id;
                            return view('answer.create',$data);
                        } else {
                            $lastQuestion = \DB::table('survey_question')
                                ->where('campaign_id',$campaign_id)
                                ->where('question_input_type_id','14')
                                ->first();
                            $activity = array(
                                'user_id' => $user_id,
                                'campaign_id' => $cam_name->id,
                                'activity_date' => $now,
                                'campaign_incentive_amount' => $cam_name->campaign_incentive_amount,
                                'campaign_incentive_point' => $cam_name->campaign_incentive_point,
                                'activity_description' => $cam_name->campaign_title,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>$user_id,
                                'updated_by' =>$user_id,
                            );
                            $user_activity = \DB::table('user_activity')->insert($activity);
                            if($user_activity){
                                \DB::table('question_answer')
                                    ->where('answer_user_id',$user_id)
                                    ->where('campaign_id',$campaign_id)
                                    ->where('is_completed',0)
                                    ->update(array(
                                        'is_completed' => 1
                                    ));
                            }
                            //return \Redirect::to('admin/survey/campaign')->with('message',"Question end.Thanks for your answer");
                            return \Redirect::to('admin/question/answer/create/'.$campaign_id.'/'.$lastQuestion->question_page_no.'/'.$user_id)
                                ->with('message',"Welcome to next page");
                        }
                    }
                } else {
                    $data['questions'] = $questions;
                    $data['campaign_id'] = $campaign_id;
                    $data['page_number'] = $page_number;
                    $data['cam_name'] = $cam_name;
                    $data['questionInput'] = $questionInput;
                    $data['page_title'] = $this->page_title;
                    $data['user_id'] = $user_id;
                    return view('answer.create',$data);
                }
            }
        } else {
            return redirect()->back()->with('errormessage','Sorry question(s) not found');
        }

    }

    public function store(Request $request,$user_id)
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
        $inputTypeIds = array('4','10','11');
        try {
            if (!empty($request->input('answer_option_group_value'))
                && is_array($request->input('answer_option_group_value'))){
                foreach($_POST['answer_option_group_value'] as $key => $val) {
                    $option_group = \DB::table('question_option_group')
                        ->leftjoin('survey_question','question_option_group.option_question_id','=','survey_question.id')
                        ->select('question_option_group.*','survey_question.question_title')
                        ->where('question_option_group.id',$val)
                        ->first();
                    if(!empty($option_group) &&count($option_group) > 0) {
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
                        $latestAnswer = \DB::table('question_answer')
                            ->where('answer_user_id',$user_id)
                            ->where('campaign_id',$campaign_id)
                            ->where('answer_question_id',$option_group->option_question_id)
                            ->where('answer_input_type_id',$option_group->option_input_type_id)
                            ->where('answer_option_group_id',$option_group->id)
                            ->first();
                        if(count($latestAnswer) > 0) {
                            $question_answer_update = array(
                                'answer_user_id' => $user_id,
                                'campaign_id' => $campaign_id,
                                'answer_question_id' => $option_group->option_question_id,
                                'answer_question_title' =>$option_group->question_title,
                                'answer_input_type_id' => $option_group->option_input_type_id,
                                'answer_option_group_id' => $option_group->id,
                                'answer_option_group_name' => $answer_option_group_name,
                                'answer_option_group_value' => $answer_option_group_value,
                                'updated_at' =>$now,
                                'updated_by' =>$user_id,
                            );
                            \DB::table('question_answer')
                                ->where('id',$latestAnswer->id)
                                ->update($question_answer_update);
                        } else {
                            $question_answer = array(
                                'answer_user_id' => $user_id,
                                'campaign_id' => $campaign_id,
                                'answer_question_id' => $option_group->option_question_id,
                                'answer_question_title' =>$option_group->question_title,
                                'answer_input_type_id' => $option_group->option_input_type_id,
                                'answer_option_group_id' => $option_group->id,
                                'answer_option_group_name' => $answer_option_group_name,
                                'answer_option_group_value' => $answer_option_group_value,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>$user_id,
                                'updated_by' =>$user_id,
                            );
                            \DB::table('question_answer')->insert($question_answer);
                            $key = $key +1;
                        }
                    } else {
                        if($request->question_input_type_id == 13) {
                            return \Redirect::to('admin/question/answer/create/'.$campaign_id.'/'.$in_page_number.'/'.$user_id)
                                ->with('message',"Welcome to next page");
                        } else {
                            \DB::table('question_answer')
                                ->where('answer_user_id',$user_id)
                                ->where('campaign_id',$campaign_id)
                                ->where('is_completed',0)
                                ->update(array(
                                    'is_completed' => 1
                                ));
                            return \Redirect::to('admin/survey/campaign')->with('message',"Question end.Thanks for your answer");
                        }
                    }
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
                                'user_id' => $user_id,
                                'type' => 'mask',
                                'total_option' => $key,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>$user_id,
                                'updated_by' =>$user_id,
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
                                'user_id' => $user_id,
                                'type' => 'rmask',
                                'total_option' => $key,
                                'created_at' =>$now,
                                'updated_at' =>$now,
                                'created_by' =>$user_id,
                                'updated_by' =>$user_id,
                            );
                            \DB::table('count_mask_rmask')->insert($count);
                        }
                    }
                    return \Redirect::to('admin/question/answer/create/'.$campaign_id.'/'.$in_page_number.'/'.$user_id)
                        ->with('message',"Welcome to next page");
                } else {
                    $campaign_information = \DB::table('survey_campaign')
                        ->where('id',$campaign_id)
                        ->first();
                    $activity = array(
                        'user_id' => $user_id,
                        'campaign_id' => $campaign_information->id,
                        'activity_date' => $now,
                        'campaign_incentive_amount' => $campaign_information->campaign_incentive_amount,
                        'campaign_incentive_point' => $campaign_information->campaign_incentive_point,
                        'activity_description' => $campaign_information->campaign_title,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>$user_id,
                        'updated_by' =>$user_id,
                    );
                    $user_activity = \DB::table('user_activity')->insert($activity);
                    if($user_activity){
                        \DB::table('question_answer')
                            ->where('answer_user_id',$user_id)
                            ->where('campaign_id',$campaign_id)
                            ->where('is_completed',0)
                            ->update(array(
                                'is_completed' => 1
                            ));
                    }
                    return \Redirect::to('admin/survey/campaign')->with('message',"Question end.Thanks for your answer");
                }
            } else {
                return redirect()->back()->with('errormessage','Please give answer at least one question');
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
