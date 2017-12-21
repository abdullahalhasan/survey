<?php

namespace App\Http\Controllers;

use App\CampaignCategory;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use DB;

class QuestionController extends Controller
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

    /**
     * get all question
     *
     * @return HTML view Response
     */
    public function index()
    {
        $questions = \App\Question::getAllSurveyQuestion();
        $questions->setPath(url('admin/question'));
        $question_pagination = $questions->render();
        $data['question_pagination'] = $question_pagination;
        $data['questions'] = $questions;
        $data['page_title'] = $this->page_title;
        return view('question.index', $data);
    }

    /**
     * creating form for creating question
     * get all category.
     *
     * @param int $campaign_id
     * @return HTML view response.
     */
    public function create($campaign_id)
    {
        $page_numbers = \DB::table('page_setting')
            ->where('campaign_id',$campaign_id)
            ->orderBy('page_number','ASC')
            ->get();
        $data['campaign_id'] = $campaign_id;
        $data['page_numbers'] = $page_numbers;
        $data['page_title'] = $this->page_title;
        return view('question.create',$data);
    }
   /* public function create($campaign_id)
    {
        $data['input_types'] = \App\Common::getAllQuestionInputType();
        $questions = \DB::table('survey_question')->get();
        $data['questions'] = $questions;
        $data['campaign_id'] = $campaign_id;
        $data['page_title'] = $this->page_title;
        return view('question.create',$data);
    }*/

    public function ajaxCreate($campaign_id,$page_number)
    {
        $data['questions'] = \DB::table('survey_question')
            ->where('campaign_id',$campaign_id)
            ->get();
        $data['input_types'] = \App\Common::getAllQuestionInputType();
        $data['campaign_id'] = $campaign_id;
        $data['page_number'] = $page_number;
        $data['page_title'] = $this->page_title;
        return view('question.ajax-create',$data);
    }

    /**
     * check post data, if failed redirect with error
     * store data into survey_question table.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request )
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'question_input_type_id' => 'required',
            'campaign_id' => 'required',
            'question_title' => 'required',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $campaign_id = $request->input('campaign_id');
        $page_number = $request->input('page_number');
        $data = array(
            'question_input_type_id' => $request->input('question_input_type_id'),
            'campaign_id' => $campaign_id,
            'question_title' => $request->input('question_title'),
            'question_help_text' => $request->input('question_help_text'),
            'question_answer_require' => $request->input('question_answer_require'),
            'masking_enable' => $request->input('masking_enable'),
            'remasking_enable' => $request->input('remasking_enable'),
            'option_random' => $request->input('option_random'),
            'question_page_no' => $page_number,
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>\Auth::user()->id,
            'updated_by' =>\Auth::user()->id,
        );
        \DB::beginTransaction();
        try {
            $question_id = \DB::table('survey_question')->insertGetId($data);
            $ex_input_type_id = array(1,2,3,4,10,12);
            $ex_input_type_ForSM = array(5,6);
            $ex_input_type_Multi = array(7);
            $ex_inputNumberPercentage = array(8,9);
            $ex_input_type_ForStar = array(11);
            $ex_input_type_ForScaleStatic = array(15);
            if (isset($_POST['masking_enable'])) {
                $mask_input_id = $request->input('mask_question_id');
                $mask_question_input_value = \DB::table('question_option_group')
                    ->where('option_question_id',$mask_input_id)
                    ->get();
                foreach ($mask_question_input_value as $input) {
                    $option_group_data = array(
                        'option_question_id' => $question_id,
                        'option_question_mask_ref_id' => $mask_input_id,
                        'question_option_name' => $input->question_option_name,
                        'question_option_value'=> $input->question_option_value,
                        'option_input_type_id' => $request->input('question_input_type_id'),
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->id,
                        'updated_by' =>\Auth::user()->id,
                    );
                    \DB::table('question_option_group')->insert($option_group_data);
                }
                $mask_meta = array(
                    'question_id' => $question_id,
                    'campaign_id' => $campaign_id,
                    'meta_question_field_name' => 'masked_question_id',
                    'meta_question_field_value' => $mask_input_id,
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    'created_by' =>\Auth::user()->id,
                    'updated_by' =>\Auth::user()->id,
                );
                \DB::table('question_meta')->insert($mask_meta);
            }  else if (isset($_POST['remasking_enable'])) {
                $re_mask_input_id = $request->input('re_mask_question_id');
                $re_mask_question_input_value = \DB::table('question_option_group')
                    ->where('option_question_id',$re_mask_input_id)
                    ->get();
                foreach ($re_mask_question_input_value as $input) {
                    $option_group_data = array(
                        'option_question_id' => $question_id,
                        'option_question_remask_ref_id' => $re_mask_input_id,
                        'question_option_name' => $input->question_option_name,
                        'question_option_value'=> $input->question_option_value,
                        'option_input_type_id' => $request->input('question_input_type_id'),
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->id,
                        'updated_by' =>\Auth::user()->id,
                    );
                    \DB::table('question_option_group')->insert($option_group_data);
                }
                $re_mask_meta = array(
                    'question_id' => $question_id,
                    'campaign_id' => $campaign_id,
                    'meta_question_field_name' => 're_masked_question_id',
                    'meta_question_field_value' => $re_mask_input_id,
                    'created_at' =>$now,
                    'updated_at' =>$now,
                    'created_by' =>\Auth::user()->id,
                    'updated_by' =>\Auth::user()->id,
                );
                \DB::table('question_meta')->insert($re_mask_meta);
            } else if(in_array($request->input('question_input_type_id'),$ex_input_type_id)) {
                if (
                    !empty($request->input('question_option_name')) && is_array($request->input('question_option_name'))
                ) {
                    foreach ($_POST['question_option_name'] as $key => $val) {
                        $option_group_data = array(
                            'question_option_name' => $val,
                            'question_option_value' => $key + 1,
                            'option_input_type_id' => $request->input('question_input_type_id'),
                            'option_question_id' => $question_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                            'created_by' => \Auth::user()->id,
                            'updated_by' => \Auth::user()->id,
                        );
                        \DB::table('question_option_group')->insert($option_group_data);
                    }
                }
            } else if(in_array($request->input('question_input_type_id'),$ex_input_type_Multi)) {
                if (
                    !empty($request->input('question_option_name')) && is_array($request->input('question_option_name'))
                ) {
                    foreach ($_POST['question_option_name'] as $key => $val) {
                        $option_group_data = array(
                            'question_option_name' => $val,
                            'option_input_type_id' => $request->input('question_input_type_id'),
                            'option_question_id' => $question_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                            'created_by' => \Auth::user()->id,
                            'updated_by' => \Auth::user()->id,
                        );
                        \DB::table('question_option_group')->insert($option_group_data);
                    }
                }
            } else if(in_array($request->input('question_input_type_id'),$ex_input_type_ForStar)) {
                $question_option_name_value = $request->input('question_option_name_value');
                if (
                    !empty($request->input('question_option_name')) && is_array($request->input('question_option_name'))
                ) {
                    foreach ($_POST['question_option_name'] as $key => $val) {
                        $option_group_data = array(
                            'question_option_name' => $val,
                            'option_input_type_id' => $request->input('question_input_type_id'),
                            'option_question_id' => $question_id,
                            'question_option_value' => $question_option_name_value,
                            'created_at' => $now,
                            'updated_at' => $now,
                            'created_by' => \Auth::user()->id,
                            'updated_by' => \Auth::user()->id,
                        );
                        \DB::table('question_option_group')->insert($option_group_data);
                    }
                }
            } else if(in_array($request->input('question_input_type_id'),$ex_input_type_ForScaleStatic)) {
                $question_option_name = $request->input('question_option_name_static');
                if (
                    !empty($request->input('question_option_name_static')) && is_array($request->input('question_option_name_static'))
                ) {
                    foreach ($_POST['question_option_name_static'] as $key => $val) {
                        $option_group_data = array(
                            'question_option_name' => $val,
                            'option_input_type_id' => $request->input('question_input_type_id'),
                            'option_question_id' => $question_id,
                            'question_option_value' => $question_option_name,
                            'created_at' => $now,
                            'updated_at' => $now,
                            'created_by' => \Auth::user()->id,
                            'updated_by' => \Auth::user()->id,
                        );
                        \DB::table('question_option_group')->insert($option_group_data);
                    }
                    $options = \DB::table('question_option_group')
                        ->where('option_question_id',$question_id)
                        ->where('option_input_type_id','15')
                        ->get();
                    foreach ($options as $option) {
                        
                    }
                }
            } else if (in_array($request->input('question_input_type_id'),$ex_input_type_ForSM)) {
                $option_group_data = array(
                    'option_input_type_id' => $request->input('question_input_type_id'),
                    'option_question_id' => $question_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'created_by' => \Auth::user()->id,
                    'updated_by' => \Auth::user()->id,
                );
                \DB::table('question_option_group')->insert($option_group_data);
            } else if (in_array($request->input('question_input_type_id'),$ex_inputNumberPercentage)) {
                    $option_group_data = array(
                        'option_input_type_id' => $request->input('question_input_type_id'),
                        'option_question_id' => $question_id,
                        'min' => $request->input('min'),
                        'max' => $request->input('max'),
                        'created_at' => $now,
                        'updated_at' => $now,
                        'created_by' => \Auth::user()->id,
                        'updated_by' => \Auth::user()->id,
                    );
                    \DB::table('question_option_group')->insert($option_group_data);
            } else {
                if (
                    !empty($request->input('question_option_name')) && !empty($request->input('question_option_value')) &&
                    is_array($request->input('question_option_name')) && is_array($request->input('question_option_value')) &&
                    count($request->input('question_option_name')) === count($request->input('question_option_value'))
                ) {
                    foreach($_POST['question_option_name'] as $key => $val) {
                        $option_group_data = array(
                            'question_option_name' => $val,
                            'question_option_value'=> $request->input('question_option_value')[$key],
                            'option_input_type_id' => $request->input('question_input_type_id'),
                            'option_question_id' => $question_id,
                            'created_at' =>$now,
                            'updated_at' =>$now,
                            'created_by' =>\Auth::user()->id,
                            'updated_by' =>\Auth::user()->id,
                        );
                        \DB::table('question_option_group')->insert($option_group_data);
                    }
                }
            }
            \App\System::EventLogWrite('insert,survey Question ',json_encode($data));
            \DB::commit();
            return redirect()->back()->with('message','Question Inserted Successfully !!');
        } catch (\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \DB::rollback();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage','Something is wrong !');
        }
    }

    public function checkMaskingRemasking($campaign_id,$page_number)
    {
        $question = \DB::table('survey_question')
            ->where('campaign_id',$campaign_id)
            ->where('question_page_no',$page_number)
            ->first();
        if(count($question)>0){
            if ($question->masking_enable == 1) {
                return response()->json(['status'=> 0]);
            } else if ($question->remasking_enable == 1) {
                return response()->json(['status'=> 0]);
            } else {
                return response()->json(['status'=> 1]);
            }
        } else {
            return response()->json(['status'=> 1]);
        }
    }

    /**
     * @param $campaign_id
     * @param $page_number
     * @param $question_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxPageNumberCheck($campaign_id,$page_number,$question_id)
    {
        $question = \DB::table('survey_question')
            ->where('id',$question_id)
            ->where('campaign_id',$campaign_id)
            ->first();
        if ($question->question_page_no > $page_number) {
            return response()->json(['status' => 0]);
        } else {
            return response()->json(['status' => 1]);
        }
    }

    public function maskQuestionInputOptionValue($question_id)
    {
        $mask_question_option = \DB::table('question_option_group')
            ->where('option_question_id',$question_id)
            ->get();
        if(count($mask_question_option)>0) {
            return response()->json($mask_question_option);
        } else {
            return response()->json(['status' => 0]);
        }
    }

    public function reMaskQuestionInputOptionValue($question_id)
    {
        $re_mask_question_option = \DB::table('question_option_group')
            ->where('option_question_id',$question_id)
            ->get();
        if(count($re_mask_question_option)>0) {
            return response()->json($re_mask_question_option);
        } else {
            return response()->json(['status' => 0]);
        }
    }
    /***
     * get all question input type.
     *
     * @return \Illuminate\Http\JsonResponse
     */
  /*  public function getAllQuestionInputTypeByAjax()
    {
        $inputTypes = \App\Common::getAllQuestionInputType();
        if(is_null($inputTypes)) {
            return response()->json(['status' => 0]);
        }
        return response()->json(['status' => 1, 'inputTypes' => $inputTypes]);
    }*/

    /**
     * Show survey question by $id.
     *
     * @param int $id
     * @return HTMl view Response
     */
    public function show($id)
    {
        $question = \App\Question::getSurveyQuestionById($id);
        if (is_null($question)) {
            return redirect('admin/question')->with('errormessage',"Sorry data not found");
        }
        $created_user = \DB::table('users')
            ->select('users.name')
            ->where('id',$question->created_by)
            ->first();
        $updated_user = \DB::table('users')
            ->select('users.name')
            ->where('id',$question->updated_by)
            ->first();
        $data['created_user'] = $created_user;
        $data['updated_user'] = $updated_user;
        $data['question'] = $question;
        $data['page_title'] = $this->page_title;
        return view('question.ajax-survey-question-show',$data);
    }

    public function edit($campaign_id,$id)
    {
        $question = \DB::table('survey_question')
            ->where('id',$id)
            ->where('campaign_id',$campaign_id)
            ->first();
        $data['questions'] = \DB::table('survey_question')
            ->where('campaign_id',$campaign_id)
            ->get();
        if (is_null($question)) {
            return redirect('admin/question')->with('errormessage',"Sorry data not found");
        }
        $data['input_types'] = \App\Common::getAllQuestionInputType();
        $data['question'] = $question;
        $data['page_title'] = $this->page_title;
        return view('question.ajax-edit',$data);
    }

    public function update(Request $request, $campaign_id, $question_id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'question_input_type_id' => 'required',
            'question_title' => 'required',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = array(
            'question_title' => $request->input('question_title'),
            'question_input_type_id' => $request->input('question_input_type_id'),
            'question_help_text' => $request->input('question_help_text'),
            'question_answer_require' => $request->input('question_answer_require'),
            'masking_enable' => $request->input('masking_enable'),
            'remasking_enable' => $request->input('remasking_enable'),
            'option_random' => $request->input('option_random'),
            'updated_at' =>$now,
            'updated_by' =>\Auth::user()->id,
        );
        if(isset($_POST['masking_enable'])){
            $meta_question_field_name = 'masked_question_id';
        } else if(isset($_POST['remasking_enable'])) {
            $meta_question_field_name = 're_masked_question_id';
        } else {
            $meta_question_field_name = "delete";
        }

        \DB::beginTransaction();
        try {
            \DB::table('survey_question')
                ->where('id',$question_id)
                ->where('campaign_id',$campaign_id)
                ->update($data);
            if(isset($_POST['masking_enable'])){
                $mask_input_id = $request->input('mask_question_id');
                $mask_question_input_value = \DB::table('question_option_group')
                    ->where('option_question_id',$mask_input_id)
                    ->get();
                foreach ($mask_question_input_value as $input) {
                    $option_group_data = array(
                        'option_question_id' => $question_id,
                        'option_question_mask_ref_id' => $mask_input_id,
                        'question_option_name' => $input->question_option_name,
                        'question_option_value'=> $input->question_option_value,
                        'option_input_type_id' => $request->input('question_input_type_id'),
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->id,
                        'updated_by' =>\Auth::user()->id,
                    );
                    \DB::table('question_option_group')->insert($option_group_data);
                }
                $existing_question_meta = \DB::table('question_meta')
                    ->where('question_id',$question_id)
                    ->where('meta_question_field_name','masked_question_id')
                    ->where('campaign_id',$campaign_id)
                    ->get();
                if(count($existing_question_meta)>0) {
                    $mask_meta = array(
                        'question_id' => $question_id,
                        'campaign_id' => $campaign_id,
                        'meta_question_field_name' => $meta_question_field_name,
                        'meta_question_field_value' => $mask_input_id,
                        'updated_at' =>$now,
                        'updated_by' =>\Auth::user()->id,
                    );
                    \DB::table('question_meta')
                        ->where('question_id',$question_id)
                        ->where('meta_question_field_name','masked_question_id')
                        ->where('campaign_id',$campaign_id)
                        ->update($mask_meta);
                } else {
                    $mask_meta = array(
                        'question_id' => $question_id,
                        'campaign_id' => $campaign_id,
                        'meta_question_field_name' => $meta_question_field_name,
                        'meta_question_field_value' => $mask_input_id,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->id,
                        'updated_by' =>\Auth::user()->id,
                    );
                    \DB::table('question_meta')->insert($mask_meta);
                }
            }
            if(isset($_POST['remasking_enable'])){
                $re_mask_input_id = $request->input('re_mask_question_id');
                $re_mask_question_input_value = \DB::table('question_option_group')
                    ->where('option_question_id',$re_mask_input_id)
                    ->get();
                foreach ($re_mask_question_input_value as $input) {
                    $option_group_data = array(
                        'option_question_id' => $question_id,
                        'option_question_mask_ref_id' => $re_mask_input_id,
                        'question_option_name' => $input->question_option_name,
                        'question_option_value'=> $input->question_option_value,
                        'option_input_type_id' => $request->input('question_input_type_id'),
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->id,
                        'updated_by' =>\Auth::user()->id,
                    );
                    \DB::table('question_option_group')->insert($option_group_data);
                }
                $existing_question_meta = \DB::table('question_meta')
                    ->where('question_id',$question_id)
                    ->where('meta_question_field_name','re_masked_question_id')
                    ->where('campaign_id',$campaign_id)
                    ->get();
                if(count($existing_question_meta)>0) {
                    $re_mask_meta = array(
                        'question_id' => $question_id,
                        'campaign_id' => $campaign_id,
                        'meta_question_field_name' => 're_masked_question_id',
                        'meta_question_field_value' => $re_mask_input_id,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->id,
                        'updated_by' =>\Auth::user()->id,
                    );
                    \DB::table('question_meta')
                        ->where('question_id',$question_id)
                        ->where('meta_question_field_name','re_masked_question_id')
                        ->where('campaign_id',$campaign_id)
                        ->update($re_mask_meta);
                } else {
                    $re_mask_meta = array(
                        'question_id' => $question_id,
                        'campaign_id' => $campaign_id,
                        'meta_question_field_name' => 're_masked_question_id',
                        'meta_question_field_value' => $re_mask_input_id,
                        'created_at' =>$now,
                        'updated_at' =>$now,
                        'created_by' =>\Auth::user()->id,
                        'updated_by' =>\Auth::user()->id,
                    );
                    \DB::table('question_meta')->insert($re_mask_meta);
                }
            }
            \DB::table('question_option_group')
                ->where('option_question_id',$question_id)
                ->update(array(
                    'option_input_type_id' => $request->input('question_input_type_id'),
                    'updated_at' =>$now,
                    'updated_by' =>\Auth::user()->id,
                ));
            if($meta_question_field_name == "delete") {
               \DB::table('question_meta')
                   ->where('question_id', $question_id)
                   ->delete();
            }
            \App\System::EventLogWrite('update,survey Question ',json_encode($data));
            \DB::commit();
            return redirect()->back()->with('message',"Question Updated Successfully !!");
        } catch (\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \DB::rollback();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage','Something is wrong! ');
        }
    }

    public function addQuestionOptionGroup($campaign_id, $question_id)
    {
        $question = \DB::table('survey_question')
            ->where('id',$question_id)
            ->where('campaign_id',$campaign_id)
            ->first();
        $data['question'] = $question;
        $data['page_title'] = $this->page_title;
        return view('question.ajax-option-group-add',$data);
    }

    public function storeQuestionOptionGroup(Request $request, $question_id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'question_option_name' => 'required',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $existName = \DB::table('question_option_group')
            ->where('option_question_id',$question_id)
            ->where('question_option_name',$request->input('question_option_name'))
            ->first();
        if($existName) {
            return redirect()->back()->with('errormessage','Option name already exist');
        } else {
            $data = array(
                'option_question_id' => $question_id,
                'option_input_type_id' => $request->input('option_input_type_id'),
                'question_option_name' => $request->input('question_option_name'),
                'question_option_value' => $request->input('question_option_value'),
                'created_at' =>$now,
                'updated_at' =>$now,
                'created_by' =>\Auth::user()->id,
                'updated_by' =>\Auth::user()->id,

            );
            try {
                \DB::table('question_option_group')->insert($data);
                \App\System::EventLogWrite('insert,input option group',json_encode($data));
                return redirect()->back()->with('message','Option name was successfully added.');
            } catch (\Exception $e) {
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return redirect()->back()->with('errormessage','Please edit question first');
            }
        }

    }

    public function editQuestionOptionGroup($id,$question_id)
    {
        $data['question_option_group'] = \DB::table('question_option_group')
            ->where('id',$id)
            ->where('option_question_id',$question_id)
            ->first();
        $data['page_title'] = $this->page_title;
        return view('question.ajax-option-group-edit',$data);
    }
    public function UpdateQuestionOptionGroup(Request $request, $id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'question_option_name' => 'required'
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = array(
            'question_option_name' => $request->input('question_option_name'),
            'question_option_value' => $request->input('question_option_value'),
            'updated_at' =>$now,
            'updated_by' =>\Auth::user()->id,
        );
        try {
            \DB::table('question_option_group')
                ->where('id',$id)
                ->where('option_question_id',$request->input('option_question_id'))
                ->update($data);
            \App\System::EventLogWrite('updated,input option group',json_encode($data));
            return redirect()->back()->with('message','The information was successfully updated.');
        } catch (\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage','The update was not successful.');
        }
    }

    /**
     * @param int $page_number
     * @param int $campaign_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ajaxCreateBranch($campaign_id,$page_number)
    {
        $questions = \DB::table('survey_question')
            ->where('campaign_id',$campaign_id)
            ->where('question_page_no', '<=', $page_number)
            ->get();
        $data['questions'] = $questions;
        $data['campaign_id'] = $campaign_id;
        $data['page_number'] = $page_number;
        $data['page_title'] = $this->page_title;
        return view('question.ajax-branch-create',$data);
    }

    /**
     * @param int $question_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ajaxQuestionOption($question_id)
    {
        $options = \DB::table('question_option_group')
            ->where('option_question_id',$question_id)
            ->get();
        $data['options'] = $options;
        $data['page_title'] = $this->page_title;
        return view('question.ajax-question-option',$data);
    }

    public function ajaxCheckBranch($campaign_id,$page_number)
    {
        $questions = \DB::table('survey_question')
            ->where('question_page_no',$page_number)
            ->where('campaign_id',$campaign_id)
            ->get();
        foreach ($questions as $q) {
            $ex_page_no [] = $q->question_page_no;
        }
        $questions_meta = \DB::table('question_meta')
            ->where('campaign_id',$campaign_id)
            ->where('meta_question_field_name','page_number')
            ->get();
        if (count($questions_meta) > 0) {
            foreach ($questions_meta as $meta) {
                $meta_pge_no[] = $meta->meta_question_field_value;
            }
            if(array_intersect($ex_page_no,$meta_pge_no)) {
                return response()->json(['status' => 0]);
            } else {
                return response()->json(['status' => 1]);
            }
           //echo 'yes';
        } else {
            return response()->json(['status' => 1]);
            //echo 'no';
        }
    }

    public function ajaxQuestionValueById($question_id)
    {
        $question_values = \DB::table('question_option_group')
            ->where('option_question_id',$question_id)
            ->get();
        $data['question_values'] = $question_values;
        return view('question.ajax-question-value',$data);
    }


    public function ajaxQuestionOptionJsonData($campaign_id,$page_number)
    {
        //$allInputOption = array();
        $questions = \DB::table('survey_question')
            ->where('campaign_id',$campaign_id)
            ->where('question_page_no', '<=', $page_number)
            ->get();
        foreach ($questions as $question) {
            $answerOption = \DB::table('question_option_group')
                ->where('option_question_id',$question->id)
                ->where('option_input_type_id',$question->question_input_type_id)
                ->get();
            $allInputOption [] = $answerOption;
        }
        echo json_encode(array('questions'=>$questions,'allInputOption'=>$allInputOption));
    }

    /**
     * @param Request $request
     * @param int $campaign_id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function branchStore(Request $request,$campaign_id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'reference_question_id.*' => 'required',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $page_number = $request->input('page_number');
        $existingPage = \DB::table('page_setting')
            ->where('campaign_id',$campaign_id)
            ->where('page_number','>=',$page_number)
            ->get();
        $existingQuestion = \DB::table('survey_question')
            ->where('campaign_id',$campaign_id)
            ->where('question_page_no','>=',$page_number)
            ->get();
        $newQuestion = $newPages = array(
            'campaign_id' => $campaign_id,
            'question_page_no' => $page_number,
            'branching_enable'=> 1,
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>\Auth::user()->id,
            'updated_by' =>\Auth::user()->id,
        );
        $newPages = array(
            'campaign_id' => $campaign_id,
            'page_number' => $page_number,
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>\Auth::user()->id,
            'updated_by' =>\Auth::user()->id,
        );
        \DB::beginTransaction();
        try {
            $question_id = \DB::table('survey_question')->insertGetId($newQuestion);
            $insert_new_page = \DB::table('page_setting')->insert($newPages);
            if($question_id && $insert_new_page ) {
                if (
                    !empty($request->input('reference_question_id')) && !empty($request->input('r_option_value')) &&
                    is_array($request->input('reference_question_id')) && is_array($request->input('r_option_value'))
                ) {
                    foreach($_POST['reference_question_id'] as $key => $val) {
                        $option_name = \DB::table('question_option_group')
                            ->where('option_question_id',$val)
                            ->where('question_option_value',$request->input('r_option_value')[$key])
                            ->first();
                        $branching_question_data = array(
                            'reference_question_id' => $val,
                            'r_option_value'=> $request->input('r_option_value')[$key],
                            'r_option_name' => isset($option_name->question_option_name) ? $option_name->question_option_name : '',
                            'relation_symbol' => $request->input('relation_symbol')[$key],
                            'term' =>  $request->input('term')[$key],
                            'compare_value' => $request->input('compare_value')[$key],
                            'b_question_id' => $question_id,
                            'created_at' =>$now,
                            'updated_at' =>$now,
                            'created_by' =>\Auth::user()->id,
                            'updated_by' =>\Auth::user()->id,
                        );
                        \DB::table('branching_question_condition')->insert($branching_question_data);
                    }
                }
                foreach ($existingQuestion as $question) {
                    $change_page_number = array(
                        'question_page_no' => $question->question_page_no + 1
                    );
                    \DB::table('survey_question')
                        ->where('id',$question->id)
                        ->where('campaign_id',$question->campaign_id)
                        ->update($change_page_number);
                }
                foreach ($existingPage as $page) {
                    $change_page_number = array(
                        'page_number' => $page->page_number + 1
                    );
                    \DB::table('page_setting')
                        ->where('id',$page->id)
                        ->where('campaign_id',$page->campaign_id)
                        ->update($change_page_number);
                }
                \App\System::EventLogWrite('create,branching Question ',json_encode($newQuestion));
                \DB::commit();
                return redirect()->back()->with('message',"Branching create Successfully !!");
            } else {
                return redirect()->back()->with('errormessage','Something is wroong.');
            }
        } catch(\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
             \DB::rollback();
            return redirect()->back()->with('errormessage','Branching have not create.');
        }
        
    }

    /**
     * @param int $page_number
     * @param int $campaign_id
     * @param int $question_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ajaxEditBranch($campaign_id,$page_number,$question_id)
    {

        $b_questions = \DB::table('branching_question_condition')
            ->where('b_question_id',$question_id)
            ->get();
        $questions = \DB::table('survey_question')
            ->where('campaign_id',$campaign_id)
            ->where('question_page_no', '<=', $page_number)
            ->get();
        $data['b_questions'] = $b_questions;
        $data['questions'] = $questions;
        $data['campaign_id'] = $campaign_id;
        $data['page_number'] = $page_number;
        $data['question_id'] = $question_id;
        $data['page_title'] = $this->page_title;
        return view('question.ajax-branch-edit',$data);
    }


    /**
     * @param Request $request
     * @param int $question_id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function branchUpdate(Request $request,$question_id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'reference_question_id.*' => 'required',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        \DB::beginTransaction();
        try {
                if (
                    !empty($request->input('reference_question_id')) && !empty($request->input('r_option_value')) &&
                    is_array($request->input('reference_question_id')) && is_array($request->input('r_option_value'))
                ) {
                    foreach($_POST['reference_question_id'] as $key => $val) {
                        $option_name = \DB::table('question_option_group')
                            ->where('option_question_id',$val)
                            ->where('question_option_value',$request->input('r_option_value')[$key])
                            ->first();
                        if(count($option_name) > 0) {
                            $exBQ = \DB::table('branching_question_condition')
                                ->where('reference_question_id',$request->input('reference_question_id'))
                                ->where('r_option_name',$option_name->question_option_name)
                                ->where('b_question_id',$question_id)
                                ->first();
                            if(count($exBQ) > 0) {
                                $ex_branching_question_data = array(
                                    'reference_question_id' => $val,
                                    'r_option_value'=> $request->input('r_option_value')[$key],
                                    'r_option_name' => isset($option_name->question_option_name) ? $option_name->question_option_name : '',
                                    'relation_symbol' => $request->input('relation_symbol')[$key],
                                    'term' =>  $request->input('term')[$key],
                                    'compare_value' => $request->input('compare_value')[$key],
                                    'updated_at' =>$now,
                                    'updated_by' =>\Auth::user()->id,
                                );
                                \DB::table('branching_question_condition')
                                    ->where('reference_question_id',$request->input('reference_question_id'))
                                    ->where('r_option_name',$option_name->question_option_name)
                                    ->where('b_question_id',$question_id)
                                    ->update($ex_branching_question_data);
                            } else {
                                $branching_question_data = array(
                                    'reference_question_id' => $val,
                                    'r_option_value'=> $request->input('r_option_value')[$key],
                                    'r_option_name' => isset($option_name->question_option_name) ? $option_name->question_option_name : '',
                                    'relation_symbol' => $request->input('relation_symbol')[$key],
                                    'term' =>  $request->input('term')[$key],
                                    'compare_value' => $request->input('compare_value')[$key],
                                    'b_question_id' => $question_id,
                                    'created_at' =>$now,
                                    'updated_at' =>$now,
                                    'created_by' =>\Auth::user()->id,
                                    'updated_by' =>\Auth::user()->id,
                                );
                                \DB::table('branching_question_condition')->insert($branching_question_data);
                            }
                        }
                    }
                }
                \DB::commit();
                return redirect()->back()->with('message',"Branching update Successfully !!");
        } catch(\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            \DB::rollback();
            return redirect()->back()->with('errormessage',$message);
        }

    }
    /**
     * @param int $id
     * @return bool
     */
    public function deleteBranchingConditionQuestion($id)
    {
        $d = \DB::table('branching_question_condition')
            ->where('id',$id)
            ->delete();
        if($d)   {
            echo "This item have been deleted successfully";
        } else {
            echo "This item have not been deleted";
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteQuestionOptionGroup($id)
    {
        \DB::table('question_option_group')
            ->where('id',$id)
            ->delete();
        return redirect()->back()->with('message','Delete successfully.');
    }

    /**
     * @param $campaign_id
     * @param $page_number
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pageSetup($campaign_id, $page_number)
    {
        $now = date('Y-m-d H:i:s');
        if (($campaign_id == '') || ($page_number=='') ||($page_number==0) ) {
            return response()->json(['status' => -1]);
        }
        $page = \DB::table('page_setting')
            ->where('campaign_id',$campaign_id)
            ->where('page_number',$page_number)
            ->first();
        if(count($page) > 0) {
            return response()->json(['status' => 2]);
        }
        $data = array(
            'campaign_id' => $campaign_id,
            'page_number' => $page_number,
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>\Auth::user()->id,
            'updated_by' =>\Auth::user()->id,
        );
        $page = \DB::table('page_setting')->insert($data);
        if($page) {
            return response()->json(['status'=>1]);
        } else {
            return response()->json(['status'=>0]);
        }
    }

    /**
     * @param $campaign_id
     * @param $page_number
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pageSetupAfter($campaign_id, $page_number)
    {
        $now = date('Y-m-d H:i:s');
        $page = \DB::table('page_setting')
            ->where('campaign_id',$campaign_id)
            ->where('page_number',$page_number)
            ->first();
        $existingPage = \DB::table('page_setting')
            ->where('campaign_id',$campaign_id)
            ->where('page_number','>=',$page_number)
            ->get();
        $newPages = array(
            'campaign_id' => $campaign_id,
            'page_number' => $page_number,
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>\Auth::user()->id,
            'updated_by' =>\Auth::user()->id,
        );
        if(count($page) > 0) {
            DB::beginTransaction();
            try {
                $insert_new_page = \DB::table('page_setting')->insert($newPages);
                if($insert_new_page) {
                    foreach ($existingPage as $page) {
                        $change_page_number = array(
                            'page_number' => $page->page_number + 1
                        );
                        \DB::table('page_setting')
                            ->where('id',$page->id)
                            ->where('campaign_id',$page->campaign_id)
                            ->update($change_page_number);
                        \DB::table('survey_question')
                            ->where('question_page_no',$page_number)
                            ->where('campaign_id',$page->campaign_id)
                            ->update(array(
                                'question_page_no' => $page->page_number + 1
                            ));
                    }
                }
                \DB::commit();
                return response()->json(['status' => 1]);
            } catch (\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                \DB::rollback();
                return response()->json($message);
            }
        } else {
            $insert = \DB::table('page_setting')->insert($newPages);
            return response()->json(['status' => 1]);
        }
    }


    public function pageDelete($id,$campaign_id,$page_number)
    {
        $existingPage = \DB::table('page_setting')
            ->where('campaign_id',$campaign_id)
            ->where('page_number','>',$page_number)
            ->get();
        $exQuestions =  \DB::table('survey_question')
            ->where('campaign_id',$campaign_id)
            ->where('question_page_no','>',$page_number)
            ->get();
        $questions =  \DB::table('survey_question')
            ->where('campaign_id',$campaign_id)
            ->where('question_page_no',$page_number)
            ->get();
        try {
            $pageDelete = \DB::table('page_setting')
                ->where('campaign_id',$campaign_id)
                ->where('page_number',$page_number)
                ->where('id',$id)
                ->delete();
            if($pageDelete){
                foreach ($existingPage as $page) {
                    $page_diff = $page->page_number - $page_number;
                    if ($page_diff == '1') {
                        $change_page_number = array(
                            'page_number' => $page->page_number - 1
                        );
                        \DB::table('page_setting')
                            ->where('id',$page->id)
                            ->where('campaign_id',$page->campaign_id)
                            ->update($change_page_number);
                    }
                }
                $deleteQuestion = \DB::table('survey_question')
                    ->where('campaign_id',$campaign_id)
                    ->where('question_page_no',$page_number)
                    ->delete();
                if($deleteQuestion) {
                    foreach ($exQuestions as $exQuestion) {
                        $q_page_diff = $exQuestion->question_page_no - $page_number;
                        if ($q_page_diff == '1') {
                            $change_page_number = array(
                                'question_page_no' => $exQuestion->question_page_no - 1
                            );
                            \DB::table('survey_question')
                                ->where('id',$exQuestion->id)
                                ->where('campaign_id',$exQuestion->campaign_id)
                                ->update($change_page_number);
                        }
                    }
                    foreach ($questions as $question) {
                        $details = \DB::table('question_option_group')
                            ->where('option_question_id',$question->id)
                            ->where('option_input_type_id',$question->question_input_type_id)
                            ->get();
                        \DB::table('question_option_group')
                            ->where('option_question_id',$question->id)
                            ->where('option_input_type_id',$question->question_input_type_id)
                            ->delete();
                        foreach ($details as $detail) {
                            \DB::table('question_option_group_details')
                                ->where('question_option_group_id',$detail->id)
                                ->delete();
                        }
                        \DB::table('branching_question_condition')
                            ->where('b_question_id',$question->id)
                            ->delete();
                        \DB::table('question_meta')
                            ->where('question_id',$question->id)
                            ->delete();
                    }

                }
            }
            echo "Page number have been deleted successfully ...";
        } catch (\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            \DB::rollback();
            echo $message;
        }
    }
    public function questionDelete($campaign_id, $question_id)
    {
        $option_groups = \DB::table('question_option_group')
            ->where('option_question_id',$question_id)
            ->get();
        $question = \DB::table('survey_question')
            ->where('campaign_id',$campaign_id)
            ->where('id',$question_id)
            ->delete();
        if($question){
            foreach ($option_groups as $group) {
                \DB::table('question_option_group_details')
                    ->where('question_option_group_id',$group->id)
                    ->delete();
            }
            \DB::table('branching_question_condition')
                ->where('b_question_id',$question_id)
                ->delete();
            \DB::table('question_option_group')
                ->where('option_question_id',$question_id)
                ->delete();
            \DB::table('question_meta')
                ->where('question_id',$question_id)
                ->delete();
            echo "Question have been deleted successfully ...";
        } else {
            echo 'Question has been not deleted';
        }
    }
    public function questionOptionGroupDelete($id,$question_id)
    {
        $option = \DB::table('question_option_group')
            ->where('id',$id)
            ->where('option_question_id',$question_id)
            ->delete();
        if($option) {
            $option_details = \DB::table('question_option_group_details')
                ->where('question_option_group_id',$id)
                ->get();
            if(!empty($option_details) && count($option_details)> 0) {
                foreach ($option_details as $group) {
                    \DB::table('question_option_group_details')
                        ->where('id',$group->id)
                        ->delete();
                }
            }
            echo "Question have been deleted successfully ...";
        } else {
            echo 'Question has been not deleted';
        }
    }
    public function checkRequest()
    {
        $now = date('Y-m-d H:i:s');
        $data = \File::get(storage_path('callLog.txt'));
        $call_logArray = json_decode($data, true);

        foreach ($call_logArray as $key => $value) {
            $ex_cal_log = \DB::table('user_sms_log_history')
                ->where('contact_number',$value['contact_number'])
                ->where('call_time',$value['call_time'])
                ->first();
            if(is_null($ex_cal_log)) {
               $data = array(
                    'user_id' => 10,
                    'user_mobile' => '01716920198',
                    'contact_name' => $value['contact_name'],
                    'contact_number' => $value['contact_number'],
                    'call_type' => $value['call_type'],
                    'call_time' => $value['call_time'],
                    'call_duration' => $value['call_duration'],
                    'created_at' =>$now,
                    'updated_at' =>$now,
                );
               \DB::table('user_sms_log_history')->insert($data);
            } else {

            }
        }
    }

    public function optionGroupDetails($id)
    {
        $question_group = \DB::table('question_option_group')
            ->where('id',$id)
            ->first();
        $data['question_group'] = $question_group;
        return view('question.ajax-label-name',$data);
    }

    public function optionGroupDetailsSubmit(Request $request, $id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'o_name.*' => 'required',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        try {
            if (
                !empty($request->input('o_name')) && is_array($request->input('o_name'))
            ) {
                foreach ($_POST['o_name'] as $key => $val) {
                    $option_group_data_details = array(
                        'o_name' => $val,
                        'o_value' => $key + 1,
                        'question_option_group_id' => $id,
                        'created_at' => $now,
                        'updated_at' => $now,
                        'created_by' => \Auth::user()->id,
                        'updated_by' => \Auth::user()->id,
                    );
                    \DB::table('question_option_group_details')->insert($option_group_data_details);
                }
            }
            return redirect()->back()->with('message','Label name inserted successfully !!');
        } catch (\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage','Label name not inserted');
        }
    }
    public function editOptionLabel($option_id)
    {
        $question_option_group = \DB::table('question_option_group')
            ->where('id',$option_id)
            ->first();
        $question_option_group_details =  \DB::table('question_option_group_details')
            ->where('question_option_group_id',$option_id)
            ->get();
        $data['question_option_group'] = $question_option_group;
        $data['question_option_group_details'] = $question_option_group_details;
        return view('question.ajax-label-name-edit',$data);
    }

    public function updateOptionLabel(Request $request, $option_id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'o_name.*' => 'required',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $question_option_group = array(
            'question_option_name' => $request->input('question_option_name'),
            'updated_at' => $now,
            'updated_by' => \Auth::user()->id,
        );
        \DB::beginTransaction();
        try {
            \DB::table('question_option_group')
                ->where('id',$option_id)
                ->update($question_option_group);
            if (
                !empty($request->input('o_name')) && !empty($request->input('question_option_group_detail_id')) &&
                is_array($request->input('o_name')) && is_array($request->input('question_option_group_detail_id')) &&
                count($request->input('o_name')) === count($request->input('question_option_group_detail_id'))
            ) {
                foreach ($_POST['o_name'] as $key => $val) {
                    $existing_question_option_group_detail = \DB::table('question_option_group_details')
                        ->where('id',$request->input('question_option_group_detail_id')[$key])
                        ->first();
                    if(count($existing_question_option_group_detail) > 0) {
                        $option_group_data_update_details = array(
                            'o_name' => $val,
                            'question_option_group_id' => $option_id,
                            'updated_at' =>$now,
                            'updated_by' =>\Auth::user()->id,
                        );
                        \DB::table('question_option_group_details')
                            ->where('id',$request->input('question_option_group_detail_id')[$key])
                            ->where('question_option_group_id',$option_id)
                            ->update($option_group_data_update_details);
                    } else {
                        $option_group_data_details = array(
                            'o_name' => $val,
                            'o_value' => $key + 1,
                            'question_option_group_id' => $option_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                            'created_by' => \Auth::user()->id,
                            'updated_by' => \Auth::user()->id,
                        );
                        \DB::table('question_option_group_details')->insert($option_group_data_details);
                    }
                }
            }
            \App\System::EventLogWrite('insert,survey Question ',json_encode($question_option_group));
            \DB::commit();
            return redirect()->back()->with('message','Updated successfully !');
        } catch (\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            \DB::rollback();
            return redirect()->back()->with('errormessage','Updated not successfully!');
        }
    }


    public function deleteLabel($id)
    {
        $delete = \DB::table('question_option_group_details')
            ->where('id',$id)
            ->delete();
        if($delete) {
            return redirect()->back()->with('message','Label name has been deleted successfully !!');
        } else {
            return redirect()->back()->with('errormessage','Label name dit not delete');
        }
    }

}
