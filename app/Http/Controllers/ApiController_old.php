<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Class constructor.
     * get current route name for page title
     * data write to access_log table.
     *
     * @param Request $request;
     */
    public function __construct(Request $request)
    {
        $this->page_title = $request->route()->getName();
        //$this->request_id =\App\Api::RequestLogWrite(\Request::all());
    }

    /**
     * Get access token.
     *
     * @param Request $request
     * @return mixed
     */
    public function getAccessToken(Request $request)
    {
        $now=date('Y-m-d H:i:s');
        try {
            $accessinfo = $request->input('accessinfo');
            $imei_no= isset($accessinfo['imei_no']) ? \App\Api::IMEIChecker($accessinfo['imei_no']):'';
            $app_key= isset($accessinfo['app_key']) ? \App\Api::AppKeyChecker($accessinfo['app_key']):'';
            $uuid = \Webpatser\Uuid\Uuid::generate(4);
            if (!empty($imei_no) && !empty($app_key)) {
                $get_info = \DB::table('app_token')
                    ->where('imei_no',$imei_no)
                    ->where('app_key',$app_key)
                    ->first();
                if (empty($get_info)) {
                    $app_token_data=[
                        "imei_no"=>$imei_no,
                        "app_key"=>$app_key,
                        "access_token"=>$uuid->string,
                        "client_ip"=>$accessinfo['access_client_ip'],
                        "access_browser"=>$accessinfo['access_browser'],
                        "access_city"=>$accessinfo['access_city'],
                        "access_division"=>$accessinfo['access_division'],
                        "access_country"=>$accessinfo['access_country'],
                        "referenceCode"=>$now,
                        "token_status"=>1,
                        "created_at"=>$now,
                        "updated_at"=>$now,
                    ];
                    $response["success"]= [
                        "statusCode"=> 200,
                        "successMessage"=> "You have created access token successfully",
                        "serverReferenceCode"=>$now
                    ];
                    $response["access_token"]=$uuid->string;
                    $requestlog_update_data=[
                        "request_response"=>json_encode($response),
                        "updated_at"=>$now,
                    ];
                    \DB::table('request_log')
                        ->where('request_id',$this->request_id)
                        ->update($requestlog_update_data);
                    \DB::table('app_token')->insert($app_token_data);
                    \App\Api::ResponseLogWrite('insert,app_token',json_encode($app_token_data));
                    return response()->json($response);
                } else {
                    $response["success"]= [
                        "statusCode"=> 200,
                        "successMessage"=> "You have already access token",
                        "serverReferenceCode"=>$now
                    ];
                    $response["access_token"]=$get_info->access_token;
                    $request_log_update_data=[
                        "request_response"=>json_encode($response),
                        "updated_at"=>$now,
                    ];
                    \DB::table('request_log')
                        ->where('request_id',$this->request_id)
                        ->update($request_log_update_data);
                    \App\Api::ResponseLogWrite('existing,app_token',json_encode($response));
                    return response()->json($response);
                }
            }
        } catch (\Exception $e) {
            $response["errors"]= [
                "statusCode"=> 501,
                "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                "serverReferenceCode"=> $now,
            ];

            \DB::table('request_log')
                ->where('request_id',$this->request_id)
                ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return response()->json($response);
        }
    }

    /**
     *
     * @param  Request $request
     * @return Response.
     */
    public function authPostLogin(Request $request)
    {
        $now=date('Y-m-d H:i:s');
        try {
            $accessinfo = $request->input('accessinfo');
            $logininfo = $request->input('logininfo');
            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
            $get_info = \DB::table('app_token')
                ->where('imei_no',$imei_no)
                ->where('access_token',$access_token)
                ->first();
            if (!empty($get_info) && !empty($logininfo)) {
                $user_email = $logininfo['email'];
                $password = $logininfo['password'];
                $user_platform = $logininfo['user_platform'];
                $user_info=\DB::table('users')
                    ->where('email',$user_email)
                    ->where('status','active')
                    ->first();
                if (!empty($user_info)) {
                    $credentials = [
                        'email' =>$user_email,
                        'password'=>$password,
                    ];
                    if (\Auth::attempt($credentials)) {
                        $response["success"]= [
                            "statusCode"=> 200,
                            "successMessage"=> "You have logged in successfully",
                            "serverReferenceCode"=>$now
                        ];
                        $response["logininfo"]= $user_info;
                        $requestlog_update_data=[
                            "request_response"=>json_encode($response),
                            "updated_at"=>$now,
                        ];
                        $user_update_data=[
                            "user_platform"=>$user_platform,
                            "updated_at"=>$now,
                        ];
                        \DB::table('users')->where('id', $user_info->id)
                            ->update($user_update_data);
                        \DB::table('request_log')->where('request_id',$this->request_id)
                            ->update($requestlog_update_data);
                        \App\Api::ResponseLogWrite('You have logged in successfully',json_encode($response));
                        return response()->json($response);
                    } else {
                        $response["errors"]= [
                            "statusCode"=> 403,
                            "errorMessage"=> "Username or password is incorrect",
                            "serverReferenceCode"=> $now
                        ];
                        \DB::table('request_log')->where('request_id',$this->request_id)
                            ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                        \App\Api::ResponseLogWrite('',json_encode($response));
                        return response()->json($response);
                    }
                } else {
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "User information not found",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')
                        ->where('request_id',$this->request_id)
                        ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Incorrect combinations.Please try again.',json_encode($response));
                    return response()->json($response);
                }
            } else {
                $response["errors"]= [
                    "statusCode"=> 403,
                    "errorMessage"=> "IMEI Number or Access Token is invalid",
                    "serverReferenceCode"=> $now
                ];
                \DB::table('request_log')
                    ->where('request_id',$this->request_id)
                    ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($response));
                return response()->json($response);
            }
        } catch (\Exception $e) {
            $response["errors"]= [
                "statusCode"=> 501,
                "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                "serverReferenceCode"=> $now,
            ];
            \DB::table('request_log')
                ->where('request_id',$this->request_id)
                ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return response()->json($response);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userRegistration(Request $request)
    {
        $now=date('Y-m-d H:i:s');
        try {
            $accessinfo = $request->input('accessinfo');
            $userinfo = $request->input('userinfo');
            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
            $get_info=\DB::table('app_token')
                ->where('imei_no',$imei_no)
                ->where('access_token',$access_token)
                ->first();
            if(!empty($get_info) && !empty($userinfo)){
                $existing_user =\DB::table('users')->where('email',$userinfo['email'])->first();
                if (!empty($existing_user)) {
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "You have already registered user",
                        "serverReferenceCode"=> $now
                    ];
                    \DB::table('request_log')
                        ->where('request_id',$this->request_id)
                        ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('You have already registered user',json_encode($response));
                    return response()->json($response);
                } else {
                    $slug=explode(' ', strtolower($userinfo['name']));
                    $name_slug=implode('.', $slug);
                    $registration = array(
                        'name' => $userinfo['name'],
                        'name_slug' => $name_slug,
                        'user_type' => 'normal_user',
                        'user_role' => 'normal_user',
                        'user_profile_image' => '',
                        'login_status' => 0,
                        'status' => 'active',
                        'email' => $userinfo['email'],
                        'password' => bcrypt($userinfo['password']),
                        'created_at' => $now,
                        'updated_at' => $now,
                    );
                    \DB::table('users')->insert($registration);
                    \App\System::EventLogWrite('insert,users',json_encode($registration));
                    $response["success"]= [
                        "statusCode"=> 200,
                        "successMessage"=> "You have successfully registered",
                        "serverReferenceCode"=>$now
                    ];
                    $response["userinfo"]=$registration;
                    $requestlog_update_data=[
                        "request_response"=>json_encode($response),
                        "updated_at"=>$now,
                    ];
                    \DB::table('request_log')
                        ->where('request_id',$this->request_id)
                        ->update($requestlog_update_data);
                    return response()->json($response);
                }
            } else {
                $response["errors"]= [
                    "statusCode"=> 403,
                    "errorMessage"=> "IMEI Number or Access Token is invalid",
                    "serverReferenceCode"=> $now
                ];
                \DB::table('request_log')
                    ->where('request_id',$this->request_id)
                    ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($response));
                return response()->json($response);
            }
        } catch (\Exception $e) {
            $response["errors"]= [
                "statusCode"=> 501,
                "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                "serverReferenceCode"=> $now,
            ];
            \DB::table('request_log')
                ->where('request_id',$this->request_id)
                ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return response()->json($response);
        }
    }

    public function profileUpdate(Request $request)
    {
        $now=date('Y-m-d H:i:s');
        try {
            $accessinfo = $request->input('accessinfo');
            $userinfo = $request->input('userinfo');
            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
            $get_info=\DB::table('app_token')
                ->where('imei_no',$imei_no)
                ->where('access_token',$access_token)
                ->first();

            if(!empty($get_info)){
                $user_id = $accessinfo['user_id'];
                $user = \DB::table('users')->where('id',$user_id)->first();
                if(!empty($userinfo['name'])){
                    $name=$userinfo['name'];
                    $slug=explode(' ', strtolower($name));
                    $name_slug=implode('.', $slug);
                }else{
                    $name=$user->name;
                    $name_slug=$user->name_slug;
                }
                if(!empty($userinfo['email'])){
                    $email=$userinfo['email'];
                }else{
                    $email=$user->email;
                }
                if(!empty($userinfo['user_profile_image'])){
                    $file_data = $userinfo['user_profile_image'];
                    $image_path =\App\NormalUser::AppProfileImageUpload($file_data,$name_slug);
                    $user_image=$image_path;
                }
                else{
                    $user_image=$user->user_profile_image;
                }
                $users_update_data=[
                    "name"=>$name,
                    "name_slug"=>$name_slug,
                    "user_profile_image"=>$user_image,
                    "email"=>$email,
                    "updated_at"=>$now,
                ];
                $response["success"]= [
                    "statusCode"=> 200,
                    "successMessage"=> "User profile update successfully",
                    "serverReferenceCode"=>$now
                ];
                $request_log_update_data=[
                    "request_response"=>json_encode($response),
                    "updated_at"=>$now,
                ];
                \DB::table('request_log')->where('request_id',$this->request_id)->update($request_log_update_data);
                \DB::table('users')->where('id',$accessinfo['user_id'])->update($users_update_data);
                $update_user_info = \DB::table('users')->where('id',$user_id)->first();
                $response["user_info"]= $update_user_info;
                \App\System::EventLogWrite('update,users',json_encode($users_update_data));
                \App\Api::ResponseLogWrite('insert,users',json_encode($response));
                return response()->json($response);
            } else {
                $response["errors"]= [
                    "statusCode"=> 403,
                    "errorMessage"=> "IMEI Number or Access Token is invalid",
                    "serverReferenceCode"=> $now
                ];
                \DB::table('request_log')
                    ->where('request_id',$this->request_id)
                    ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($response));
                return response()->json($response);
            }
        } catch (\Exception $e) {
            $response["errors"]= [
                "statusCode"=> 501,
                "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                "serverReferenceCode"=> $now,
            ];
            \DB::table('request_log')
                ->where('request_id',$this->request_id)
                ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return response()->json($response);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCampaign( Request $request)
    {
        $now=date('Y-m-d H:i:s');
        try {
            $accessinfo = $request->input('accessinfo');
            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
            $get_info=\DB::table('app_token')
                ->where('imei_no',$imei_no)
                ->where('access_token',$access_token)
                ->first();
            if (!empty($get_info)) {
                $surveyCampaigns= \DB::table('survey_campaign')
                    ->leftjoin('campaign_category','survey_campaign.campaign_category_id','=','campaign_category.id')
                    ->select('survey_campaign.*','campaign_category.name')
                    ->get();
                $response["surveyCampaigns"]=$surveyCampaigns;
                $response["success"]= [
                    "statusCode"=> 200,
                    "successMessage"=> "All campaign for survey",
                    "serverReferenceCode"=>$now,
                ];
                $requestlog_update_data=[
                    "request_response"=>json_encode($response["success"]),
                    "updated_at"=>$now,
                ];
                \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                \App\Api::ResponseLogWrite('All campaign for survey',json_encode($response));
                return response()->json($response);
            } else {
                $response["errors"]= [
                    "statusCode"=> 403,
                    "errorMessage"=> "IMEI Number or Access Token is invalid",
                    "serverReferenceCode"=> $now
                ];
                \DB::table('request_log')
                    ->where('request_id',$this->request_id)
                    ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($response));
                return response()->json($response);
            }
        } catch (\Exception $e) {
            $response["errors"]= [
                "statusCode"=> 501,
                "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                "serverReferenceCode"=> $now,
            ];
            \DB::table('request_log')
                ->where('request_id',$this->request_id)
                ->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return response()->json($response);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callHistory(Request $request)
    {
        $now=date('Y-m-d H:i:s');
        $call_log = $request->input('call_log');
        $user_id = $request->input('user_id');
        $user_mobile = $request->input('user_mobile');
        $call_logArray = json_decode(json_encode($call_log), true);
        try {
            foreach ($call_logArray as $key => $value) {
                $ex_cal_log = \DB::table('user_call_log_history')
                    ->where('contact_number',$value['contact_number'])
                    ->where('call_time',$value['call_time'])
                    ->first();
                if(is_null($ex_cal_log)) {
                    $data = array(
                        'user_id' => $user_id,
                        'user_mobile' => $user_mobile,
                        'contact_name' => $value['contact_name'],
                        'contact_number' => $value['contact_number'],
                        'call_type' => $value['call_type'],
                        'call_time' => $value['call_time'],
                        'call_duration' => $value['call_duration'],
                        'created_at' =>$now,
                        'updated_at' =>$now,
                    );
                    \DB::table('user_call_log_history')->insert($data);
                }
            }
           /* if($insert){
                \App\System::EventLogWrite('insert,call _log',json_encode($insert));
                $response["success"]= [
                    "statusCode"=> 200,
                    "successMessage"=> "Data inserted successfully.",
                    "serverReferenceCode"=>$now
                ];
                return response()->json($response);

            } else {
                $response["errors"]= [
                    "statusCode"=> 403,
                    "errorMessage"=> "Data already inserted.",
                    "serverReferenceCode"=> $now,
                ];
                return response()->json($response);
            }*/
            $response["success"]= [
                "statusCode"=> 200,
                "successMessage"=> "Data inserted successfully.",
                "serverReferenceCode"=>$now
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            $response["errors"]= [
                "statusCode"=> 501,
                "errorMessage"=> "Data did not insert",
                "serverReferenceCode"=> $now,
            ];
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return response()->json($response);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SMSHistory(Request $request)
    {
        $now=date('Y-m-d H:i:s');
        $message_log = $request->input('sms_log');
        $user_id = $request->input('user_id');
        $user_mobile = $request->input('user_mobile');
        $message_logArray = json_decode(json_encode($message_log), true);
        try {
            foreach ($message_logArray as $key => $value) {
                $ex_cal_log = \DB::table('user_sms_log_history')
                    ->where('sms_address',$value['address'])
                    ->where('sms_date',$value['date'])
                    ->first();
                if(is_null($ex_cal_log)) {
                    $data = array(
                        'user_id' => $user_id,
                        'user_mobile' => $user_mobile,
                        'sms_address' => $value['address'],
                        'sms_type' => $value['type'],
                        'sms_text' => $value['body'],
                        'sms_date' => $value['date'],
                        'created_at' =>$now,
                        'updated_at' =>$now,
                    );
                    \DB::table('user_sms_log_history')->insert($data);
                }
            }
            $response["success"]= [
                "statusCode"=> 200,
                "successMessage"=> "Data inserted successfully.",
                "serverReferenceCode"=>$now
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            $response["errors"]= [
                "statusCode"=> 501,
                "errorMessage"=> "Data did not insert",
                "serverReferenceCode"=> $now,
            ];
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return response()->json($response);
        }
    }


}
