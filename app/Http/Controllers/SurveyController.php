<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Question;
class SurveyController extends Controller
{
    /**
     * Class constructor.
     * get current route name for page title.
     *
     * data write to access_log table.
     */
    public function __construct(Request $request)
    {
        $this->page_title = $request->route()->getName();
        //\App\System::AccessLogWrite();
    }
    public function index()
    {
        $data['page_title'] = $this->page_title;
        return view('index',$data);
    }

    public function getAllCampaign()
    {
        $surveyCampaigns= \DB::table('survey_campaign')
            ->leftjoin('campaign_category','survey_campaign.campaign_category_id','=','campaign_category.id')
            ->select('survey_campaign.*','campaign_category.name')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $surveyCampaigns->setPath(url('all/campaign'));
        $surveyCampaignsPagination = $surveyCampaigns->render();
        $data['surveyCampaignsPagination'] = $surveyCampaignsPagination;
        $data['surveyCampaigns'] = $surveyCampaigns;
        $data['page_title'] = $this->page_title;
        return view('all-campaign',$data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function signIn()
    {
        $data['page_title'] = $this->page_title;
        return view('sign-in',$data);
    }
    /**
     * Check Admin Authentication
     * checked validation, if failed redirect with error message
     * checked auth $credentials, if failed redirect with error message
     * checked user type, if "admin" change login status.
     *
     * @param  Request $request
     * @return Response.
     */
    public function signInPost(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'user_mobile';
        $request->merge([$field => $request->input('login')]);
        if (\Auth::attempt($request->only($field, 'password'))) {
            \Session::put('email', \Auth::user()->email);
            \Session::put('last_login', Auth::user()->last_login);
            if (\Session::has('pre_login_url') ) {
                $url = \Session::get('pre_login_url');
                \Session::forget('pre_login_url');
                return redirect($url);
            } else if (\Auth::user()->user_type=="normal_user") {
                \App\User::LogInStatusUpdate("login");
                return redirect('/');
            } else {
                \App\User::LogInStatusUpdate("logout");
                \Auth::logout();
                return redirect('sign-in')
                    ->with('errormessage',"Sorry, You don't have permission to access this page.");
            }
        } else {
            return redirect('sign-in')
                ->with('errormessage',"Incorrect combinations.Please try again.");
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function signUp()
    {
        $data['page_title'] = $this->page_title;
        return view('sign-up',$data);
    }
    /**
     * User Registration
     * checked validation, if failed redirect with message
     * data store into users table.
     *
     * @param Request $request
     * @return Response
     */
    public function signUpPost(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'repeat_password' => 'required|in_array:password',
            'user_mobile' => 'required|regex:/^[^0-9]*(88)?0/|max:11|unique:users'
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $name = ucwords($request->input('name'));
        $slug=explode(' ', strtolower($name));
        $name_slug=implode('.', $slug);
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));
        $user_mobile = $request->input('user_mobile');
        $ex_user = \DB::table('users')->where('user_mobile',$user_mobile)->first();
        if(count($ex_user) > 0) {
            return \Redirect::to('sign-in')->with('message',"You are already registered user.Please Login");
        } else {
            $pin_number = mt_rand(1000, 9999);
            $user_meta =array($name,$email,$pin_number);
            $user_meta_serialize = serialize($user_meta);
            $registration=array(
                'name' => $name,
                'name_slug' => $name_slug,
                'user_type' => 'normal_user',
                'user_role' => 'normal_user',
                'user_profile_image' => '',
                'user_mobile' => $user_mobile,
                'login_status' => 0,
                'status' => 'active',
                'email' => $email,
                'password' => $password,
                'created_at' => $now,
                'updated_at' => $now,
            );
            \DB::beginTransaction();
            try {
                $user_id = \DB::table('users')->insertGetId($registration);
                if ($user_id) {
                    $user_meta_data = array(
                        'meta_user_id' =>$user_id,
                        'user_meta_field_name' => 'pin_number',
                        'user_meta_field_value' =>$user_meta_serialize,
                        'created_at' => $now,
                        'updated_at' => $now,
                    );
                    $ex_user_meta = \DB::table('user_meta')
                        ->where('meta_user_id',$user_id)
                        ->where('user_meta_field_name','pin_number')
                        ->first();
                    if(count($ex_user_meta) > 0) {
                        \DB::table('user_meta')
                            ->where('meta_user_id',$user_id)
                            ->where('user_meta_field_name','pin_number')
                            ->update($user_meta_data);
                    } else {
                        \DB::table('user_meta')->insert($user_meta_data);
                    }
                    \App\System::EventLogWrite('insert,users',json_encode($registration));
                    //$otp_send=\App\OTP::SendSMSForUserRegistration($user_mobile,$pin_number);
                    \DB::commit();
                    return \Redirect::to('pin/confirm/'.$user_mobile)->with('message',"A confirm code send your mobile");
                    //return redirect('sign-in')->with('message',"You have successfully registered");
                }
            } catch(\Exception $e) {
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return redirect('sign-up')->with('errormessage',$message);
            }
        }
    }

    /***
     * @param $user_mobile
     * @return mixed
     */
    public function PinConfirm($user_mobile)
    {
        $user = \DB::table('users')
            ->where('user_mobile',$user_mobile)
            ->where('mobile_verified','0')
            ->first();
        if(count($user) > 0) {
            $meta_user = \DB::table('user_meta')
                ->where('meta_user_id',$user->id)
                ->where('user_meta_field_name','pin_number')
                ->first();
            if(count($meta_user)>0){
                $data['page_title'] = $this->page_title;
                $data['user_mobile'] = $user_mobile;
                $data['meta_user'] = $meta_user;
                return \View::make('confirm-pin',$data);
            } else {
                $data['page_title'] = $this->page_title;
                $data['user_mobile'] = $user_mobile;
                return \View::make('confirm-pin',$data);
            }
        } else {
            return redirect('sign-up')->with('errormessage',"Something is wrong on user registration ! Please try again..");
        }
    }

    public function PinConfirmPost(Request $request, $user_mobile)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'pin_confirm' => 'required',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $user = \DB::table('users')
            ->where('user_mobile',$user_mobile)
            ->where('mobile_verified','0')
            ->first();
        $pin_confirm = $request->input('pin_confirm');
        if(count($user) > 0) {
            try {
                $user_meta = \DB::table('user_meta')
                    ->where('meta_user_id',$user->id)
                    ->where('user_meta_field_name','pin_number')
                    ->first();
                if(count($user_meta) > 0) {
                    $pin_info = unserialize($user_meta->user_meta_field_value);
                    $sent_pin = $pin_info[2];
                    if($sent_pin == $pin_confirm) {
                        $update_user_data = array(
                            'mobile_verified' => 1,
                            'updated_at' => $now
                        );
                        \DB::table('users')
                            ->where('id',$user->id)
                            ->update($update_user_data);
                        \Auth::loginUsingId($user->id);
                        return \Redirect::to('all/campaign')->with('message',"Mobile number verified successfully");;
                    } else {
                        return \Redirect::to('pin/confirm/'.$user_mobile)->with('errormessage',"Pin number not match");
                    }
                } else {
                    return \Redirect::to('pin/confirm/'.$user_mobile)->with('errormessage',"Pin number not valid");
                }

            } catch (\Exception $e) {
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to('pin/confirm/'.$user_mobile)->with('errormessage',"Duplicate email or Something Went Wrong On User Registration ! Please try Again..");
            }
        } else {
            return redirect('sign-up')->with('errormessage',"User not found ! Please registration first.");
        }
    }

    /**
     * @param $customer_mobile
     * @param $client_otp
     * @return mixed
     */
    public function ResendSMSForPinNumber($customer_mobile,$client_otp){
        try{
            \App\OTP::SendSMSForUserRegistration($customer_mobile,$client_otp);
            return \Redirect::back()->with('message',"Code Send Successfully");
        }catch(\Exception $e){
            \DB::rollback();
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return \Redirect::back()->with('errormessage',"Something was wrong");
        }

    }

    /**
     * @param $name_slug
     * @return mixed
     */
    public function logout($name_slug)
    {
        if (\Auth::check()) {
            $user_info = \App\User::where('email',\Auth::user()->email)->first();
            if (!empty($user_info) && ($name_slug==$user_info->name_slug)) {
                \App\User::LogInStatusUpdate("logout");
                \Auth::logout();
                return \Redirect::to('sign-in');
            } else {
                return \Redirect::to('sign-in');
            }
        } else {
            return \Redirect::to('sign-in')->with('errormessage',"Error logout");
        }
    }

    /***
     * @param $user_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editProfile($id)
    {
        $user_id = \Crypt::decrypt($id);
        $user = \DB::table('users')
            ->where('id',$user_id)
            ->first();
        if(count($user) > 0) {
            $data['page_title'] = $this->page_title;
            $data['user'] = $user;
            return view('profile',$data);
        } else {
            return redirect()->back()->with('errormessage','User not found');
        }
    }

    public function updateProfile(Request $request, $user_id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user_id.',id',
            'user_mobile' => 'required|regex:/^[^0-9]*(88)?0/|max:11|unique:users,user_mobile,'.$user_id.',id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $name = ucwords($request->input('name'));
        $slug=explode(' ', strtolower($name));
        $name_slug=implode('.', $slug);
        $email = $request->input('email');
        $user_mobile = $request->input('user_mobile');
        $pin_number = mt_rand(1000, 9999);
        $user_meta =array($name,$email,$pin_number);
        $user_meta_serialize = serialize($user_meta);
        $registration=array(
            'name' => $name,
            'name_slug' => $name_slug,
            'user_type' => 'normal_user',
            'user_role' => 'normal_user',
            'user_mobile' => $user_mobile,
            'status' => 'active',
            'email' => $email,
            'updated_at' => $now,
        );
        try {
            $user = \DB::table('users')
                ->where('id',$user_id)
                ->update($registration);
            if ($user) {
                $user_meta_data = array(
                    'meta_user_id' =>$user_id,
                    'user_meta_field_name' => 'pin_number',
                    'user_meta_field_value' =>$user_meta_serialize,
                    'created_at' => $now,
                    'updated_at' => $now,
                );
                $ex_user_meta = \DB::table('user_meta')
                    ->where('meta_user_id',$user_id)
                    ->where('user_meta_field_name','pin_number')
                    ->first();
                if(count($ex_user_meta) > 0) {
                    $client_data=unserialize($ex_user_meta->user_meta_field_value);
                    $email = $request->input('email');
                    $name = ucwords($request->input('name'));
                    $pin_number = $client_data[2];
                    $user_meta =array($name,$email,$pin_number);
                    $user_meta_serialize = serialize($user_meta);
                    $user_meta_data_update = array(
                        'meta_user_id' =>$user_id,
                        'user_meta_field_name' => 'pin_number',
                        'user_meta_field_value' =>$user_meta_serialize,
                        'updated_at' => $now,
                    );
                    \DB::table('user_meta')
                        ->where('meta_user_id',$user_id)
                        ->where('user_meta_field_name','pin_number')
                        ->update($user_meta_data_update);
                    return \Redirect::to('all/campaign')->with('message',"Profile updated successfully");
                } else {
                    \DB::table('user_meta')->insert($user_meta_data);
                    //$otp_send=\App\OTP::SendSMSForUserRegistration($user_mobile,$pin_number);
                    return \Redirect::to('pin/confirm/'.$user_mobile)->with('message',"A confirm code send your mobile");
                }
                \App\System::EventLogWrite('update,users',json_encode($registration));
            }
        } catch(\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect('sign-up')->with('errormessage',$message);
        }
    }

    public function changePassword($id)
    {
        $user_id = \Crypt::decrypt($id);
        $user = \DB::table('users')
            ->where('id',$user_id)
            ->first();
        if(count($user) > 0) {
            $data['page_title'] = $this->page_title;
            $data['user'] = $user;
            return view('change-password',$data);
        } else {
            return redirect()->back()->with('errormessage','User not found');
        }

    }

    public function updatePassword(Request $request, $user_id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'password' => 'required',
            'repeat_password' => 'required|in_array:password',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $new_password = $request->input('password');
        $repeat_password = $request->input('repeat_password');
        if ($new_password == $repeat_password) {
            $update_password=array(
                'password' => bcrypt($request->input('password')),
                'updated_at' => $now
            );
            try {
                $update_pass=\DB::table('users')->where('id', $user_id)->update($update_password);
                if($update_pass) {
                    \App\System::EventLogWrite('update,users', 'password changed');
                    return redirect('all/campaign')->with('message',"Password updated successfully !");
                }
            } catch(\Exception $e) {
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return redirect('all/campaign')->with('errormessage',"Password update failed  !");
            }
        } else {
            return redirect()->back()->with('message',"Password Combination Doesn't Match !");
        }
    }

    /**
     * @param int $campaign_id
     * @param int $page_number
     * @param int $user_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCampaign($campaign_id,$page_number,$user_id)
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
        if(count($questions)>0) {
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
                            return view('user-answer',$data);
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
                                return view('user-answer',$data);
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
                                return \Redirect::to('question/answer/'.$campaign_id.'/'.$lastQuestion->question_page_no.'/'.$user_id)
                                    ->with('message',"Welcome to next page");
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
                            return view('user-answer',$data);
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

                            return \Redirect::to('question/answer/'.$campaign_id.'/'.$lastQuestion->question_page_no.'/'.$user_id)
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
                    return view('user-answer',$data);
                }
            }
        } else {
            return redirect()->back()->with('errormessage','Sorry question(s) not found');
        }
    }

    /**
     * @param int $user_id
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function campaignAnswerPost(Request $request,$user_id)
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
                            return \Redirect::to('question/answer/'.$campaign_id.'/'.$in_page_number.'/'.$user_id)
                                ->with('message',"Welcome to next page");
                        } else {
                            \DB::table('question_answer')
                                ->where('answer_user_id',$user_id)
                                ->where('campaign_id',$campaign_id)
                                ->where('is_completed',0)
                                ->update(array(
                                    'is_completed' => 1
                                ));
                            return \Redirect::to('all/campaign')->with('message',"Question end.Thanks for your answer");
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
                    return \Redirect::to('question/answer/'.$campaign_id.'/'.$in_page_number.'/'.$user_id)
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
                    return \Redirect::to('all/campaign')->with('message',"Question end.Thanks for your answer");
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

    /**
     * @param int $user_id
     * @param int $campaign_id
     * @param int $page_number
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function answerFromApp($campaign_id,$page_number,$user_id)
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
        if(count($questions)>0) {
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
                            return view('app.answer',$data);
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
                                return view('app.answer',$data);
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
                                return \Redirect::to('app/answer/'.$campaign_id.'/'.$lastQuestion->question_page_no.'/'.$user_id)
                                    ->with('message',"Welcome to next page");
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
                            return view('app.answer',$data);
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

                            return \Redirect::to('app/answer/'.$campaign_id.'/'.$lastQuestion->question_page_no.'/'.$user_id)
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
                    return view('app.answer',$data);
                }
            }
        } else {
            return redirect()->back()->with('errormessage','Sorry question(s) not found');
        }
    }

    /**
     * @param int $user_id
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function storeWebApp(Request $request,$user_id)
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
                            return \Redirect::to('app/answer/'.$campaign_id.'/'.$in_page_number.'/'.$user_id)
                                ->with('message',"Welcome to next page");
                        } else {
                            \DB::table('question_answer')
                                ->where('answer_user_id',$user_id)
                                ->where('campaign_id',$campaign_id)
                                ->where('is_completed',0)
                                ->update(array(
                                    'is_completed' => 1
                                ));
                            return \Redirect::to('all/campaign')->with('message',"Question end.Thanks for your answer");
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
                    return \Redirect::to('app/answer/'.$campaign_id.'/'.$in_page_number.'/'.$user_id)
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
                    return \Redirect::to('all/campaign')->with('message',"Question end.Thanks for your answer");
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contactUs()
    {
        $data['page_title'] = $this->page_title;
        return view('contact',$data);
    }
    public function senMail(Request $request)
    {
        $v = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
       /* \Mail::send('emails.message',
            array(
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'subject' => $request->input('subject'),
                'message' => $request->input('message')
            ), function($message)
            {
                $message->subject(\Request::input('subject'));
                $message->from(\Request::input('email'));
                $message->to('info@mybazaar24.com','Contact');
            });*/
        return redirect()->back()->with('message','Thanks for connecting us.');
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCallLogs()
    {
        $call_logs = \DB::table('user_call_log_history')
            ->orderBy('created_at','DSC')
            ->paginate(20);
        $call_logs->setPath(url('call/logs'));
        $callLogsPagination = $call_logs->render();
        $data['callLogsPagination'] = $callLogsPagination;
        $data['call_logs'] = $call_logs;
        $data['page_title'] = $this->page_title;
        return view('call-logs',$data);
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSMSLogs()
    {
        $sms_logs = \DB::table('user_sms_log_history')
            ->orderBy('created_at','DSC')
            ->paginate(20);
        $sms_logs->setPath(url('sms/logs'));
        $smsLogsPagination = $sms_logs->render();
        $data['smsLogsPagination'] = $smsLogsPagination;
        $data['sms_logs'] = $sms_logs;
        $data['page_title'] = $this->page_title;
        return view('sms-logs',$data);
    }


}
