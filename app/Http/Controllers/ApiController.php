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
            $response["success"]= [
                "statusCode"=> 501,
                "successMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
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
    public function appLogin(Request $request)
    {
        $now=date('Y-m-d H:i:s');
        $userinfo = $request->input('userinfo');
        $user = \DB::table('users')
            ->where('email',$userinfo['login'])
            ->orWhere('user_mobile',$userinfo['login'])
            ->first();
        //return response()->json($user);
        if(count($user) > 0) {
            $credentials = [
                'user_mobile' =>$userinfo['login'],
                'password'=>$userinfo['password'],
            ];
            if (\Auth::attempt($credentials)) {
                $response["success"]= [
                    "statusCode"=> 200,
                    "successMessage"=> "successfully Login.",
                    "serverReferenceCode"=>$now,
                ];
                $response['userinfo'] = $user;
                return response()->json($response);
            } else {
                $response["success"]= [
                    "statusCode"=> 403,
                    "successMessage"=> "Incorrect combinations. Please try again.",
                    "serverReferenceCode"=> $now
                ];
                return response()->json($response);
            }
        } else {
            $response["success"]= [
                "statusCode"=> 403,
                "successMessage"=> "Invalid user or block user.",
                "serverReferenceCode"=> $now
            ];
            return response()->json($response);
        }
    }

    public function resentPinApp(Request $request)
    {
        $now=date('Y-m-d H:i:s');
        $userinfo = $request->input('userinfo');
        $user = \DB::table('users')
            ->where('user_mobile',$userinfo['user_mobile'])
            ->first();
        if(count($user) > 0) {
            $user_meta = \DB::table('user_meta')
                ->where('meta_user_id',$user->id)
                ->where('user_meta_field_name','pin_number')
                ->first();
            if(count($user_meta) > 0) {
                $meta_data=unserialize($user_meta->user_meta_field_value);
                $pin_number = $meta_data[2];
                //\App\OTP::SendSMSForUserRegistration($userinfo['user_mobile'],$pin_number);
                $response["success"]= [
                    "statusCode"=> 200,
                    "successMessage"=> "A confirm code resend your mobile",
                    "serverReferenceCode"=>$now,
                    //'pin_number' => $meta_data[2]
                ];
                $response['userinfo'] = $user;
                return response()->json($response);
            } else {
                $response["success"]= [
                    "statusCode"=> 404,
                    "successMessage"=> "Pin number not found",
                    "serverReferenceCode"=> $now
                ];
                return response()->json($response);
            }
        } else {
            $response["success"]= [
                "statusCode"=> 403,
                "successMessage"=> "Invalid user or block user.",
                "serverReferenceCode"=> $now
            ];
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
            $surveyCampaigns= \DB::table('survey_campaign')
                ->leftjoin('campaign_category','survey_campaign.campaign_category_id','=','campaign_category.id')
                ->select('survey_campaign.*','campaign_category.name')
                ->orderBy('survey_campaign.id','DESC')
                ->get();
            $response["surveyCampaigns"]=$surveyCampaigns;
            $response["success"]= [
                "statusCode"=> 200,
                "successMessage"=> "All campaign for survey",
                "serverReferenceCode"=>$now,
            ];
            return response()->json($response);

        } catch (\Exception $e) {
            $response["success"]= [
                "statusCode"=> 501,
                "successMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
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
                $response["success"]= [
                    "statusCode"=> 403,
                    "successMessage"=> "Data already inserted.",
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
            $response["success"]= [
                "statusCode"=> 501,
                "successMessage"=> "Data did not insert",
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
            $response["success"]= [
                "statusCode"=> 501,
                "successMessage"=> "Data did not insert",
                "serverReferenceCode"=> $now,
            ];
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return response()->json($response);
        }
    }

    public function AppRegistration(Request $request)
    {
        $now=date('Y-m-d H:i:s');
        $userinfo = $request->input('userinfo');
        $existing_user = \DB::table('users')
            ->where('user_mobile',$userinfo['user_mobile'])
            ->orWhere('email',$userinfo['email'])
            ->first();
        if(count($existing_user) > 0) {
            $response["success"]= [
                "statusCode"=> 302,
                "successMessage"=> "You are already registered user",
                "serverReferenceCode"=> $now,
            ];
            $response['userinfo'] = $existing_user;
            return response()->json($response);
        } else {
            if (isset($userinfo['name'])) {
                $name = ucwords($userinfo['name']);
                $slug=explode(' ', strtolower($name));
                $name_slug=implode('.', $slug);
            } else {
                $name = '';
                $name_slug = '';
            }
            $email = isset($userinfo['email']) ? $userinfo['email'] : '';
            $password = isset($userinfo['password']) ? bcrypt($userinfo['password']):'';
            $user_mobile = isset($userinfo['user_mobile']) ? $userinfo['user_mobile'] : '';
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
            try {
                $user_new_id = \DB::table('users')->insertGetId($registration);
                if ($user_new_id) {
                    $pin_number = mt_rand(1000, 9999);
                    $user_meta =array($name,$user_mobile,$pin_number);
                    $user_meta_serialize = serialize($user_meta);
                    $user_meta_data = array(
                        'meta_user_id' =>$user_new_id,
                        'user_meta_field_name' => 'pin_number',
                        'user_meta_field_value' =>$user_meta_serialize,
                        'created_at' => $now,
                        'updated_at' => $now,
                    );
                    $ex_user_meta = \DB::table('user_meta')
                        ->where('meta_user_id',$user_new_id)
                        ->where('user_meta_field_name','pin_number')
                        ->first();
                    if(count($ex_user_meta) > 0) {
                        \DB::table('user_meta')
                            ->where('meta_user_id',$user_new_id)
                            ->where('user_meta_field_name','pin_number')
                            ->update($user_meta_data);
                    } else {
                        \DB::table('user_meta')->insert($user_meta_data);
                    }
                    \App\System::EventLogWrite('insert,users',json_encode($registration));
                    //$otp_send=\App\OTP::SendSMSForUserRegistration($user_mobile,$pin_number);
                    $response["success"]= [
                        "statusCode"=> 200,
                        "successMessage"=> "A confirm code send your mobile",
                        "serverReferenceCode"=>$now,
                        "user_id" => $user_new_id
                    ];
                    return response()->json($response);
                }
            } catch(\Exception $e) {
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                $response["success"]= [
                    "statusCode"=> 501,
                    "successMessage"=> "Something is wrong",
                    "serverReferenceCode"=> $now,
                    'user_info' => $userinfo,
                    'message' => $message,
                ];
                \App\System::ErrorLogWrite($message);
                return response()->json($response);
            }
        }
    }

    public function AppPinConfirm(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $userinfo = $request->input('userinfo');
        $user = \DB::table('users')
            ->where('user_mobile',$userinfo['user_mobile'])
            ->where('mobile_verified','0')
            ->first();
        $pin_confirm = $userinfo['pin_number'];
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
                        $update_user = $user = \DB::table('users')
                            ->where('user_mobile',$userinfo['user_mobile'])
                            ->first();
                        $response["success"]= [
                            "statusCode"=> 200,
                            "successMessage"=> "Mobile number verified successfully",
                            "serverReferenceCode"=> $now,
                        ];
                        $response['userinfo'] = $update_user;
                        return response()->json($response);
                    } else {
                        $response["success"]= [
                            "statusCode"=> 504,
                            "successMessage"=> "Pin number not match",
                            "serverReferenceCode"=> $now,
                        ];
                        return response()->json($response);
                    }
                } else {
                    $response["success"]= [
                        "statusCode"=> 503,
                        "successMessage"=> "Pin number not valid",
                        "serverReferenceCode"=> $now,
                    ];
                    return response()->json($response);
                }

            } catch (\Exception $e) {
                $response["success"]= [
                    "statusCode"=> 502,
                    "successMessage"=> "Something went wrong, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return response()->json($response);
            }
        } else {
            $response["success"]= [
                "statusCode"=> 501,
                "successMessage"=> "User not found ! Please registration first.",
                "serverReferenceCode"=> $now,
            ];
            return response()->json($response);
        }
    }

    public function getUserActivities(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $userinfo = $request->input('userinfo');
        $activities = \DB::table('user_activity')
            ->where('user_id',$userinfo['user_id'])
            ->get();
        if(count($activities) > 0) {
            $response["success"]= [
                "statusCode"=> 200,
                "successMessage"=> "User activities  found ",
                "serverReferenceCode"=> $now,
            ];
            $response['activities'] = $activities;
            return response()->json($response);
        } else {
            $response["success"]= [
                "statusCode"=> 501,
                "successMessage"=> "User activities not found",
                "serverReferenceCode"=> $now,
            ];
            return response()->json($response);
        }
    }

    public function appProfileUpdate(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $userinfo = $request->input('userinfo');
        $user = \DB::table('users')
            ->where('id',$userinfo['user_id'])
            ->first();
        if(count($user) > 0) {
            if (isset($userinfo['name'])) {
                $name = ucwords($userinfo['name']);
                $slug=explode(' ', strtolower($name));
                $name_slug=implode('.', $slug);
            } else {
                $name = '';
                $name_slug = '';
            }
            $email = isset($userinfo['email']) ? $userinfo['email'] : '';
            $user_mobile = isset($userinfo['user_mobile']) ? $userinfo['user_mobile'] : '';
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
                $update_user = \DB::table('users')
                    ->where('id',$user->id)
                    ->update($registration);
                if ($update_user) {
                    $user_update_data = \DB::table('users')
                        ->where('id',$userinfo['user_id'])
                        ->first();
                    $user_meta_data = array(
                        'meta_user_id' =>$user->id,
                        'user_meta_field_name' => 'pin_number',
                        'user_meta_field_value' =>$user_meta_serialize,
                        'created_at' => $now,
                        'updated_at' => $now,
                    );
                    $ex_user_meta = \DB::table('user_meta')
                        ->where('meta_user_id',$user->id)
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
                            'meta_user_id' =>$user->id,
                            'user_meta_field_name' => 'pin_number',
                            'user_meta_field_value' =>$user_meta_serialize,
                            'updated_at' => $now,
                        );
                        \DB::table('user_meta')
                            ->where('meta_user_id',$user->id)
                            ->where('user_meta_field_name','pin_number')
                            ->update($user_meta_data_update);
                        $response["success"]= [
                            "statusCode"=> 200,
                            "successMessage"=> "Profile updated successfully",
                            "serverReferenceCode"=> $now,
                        ];
                        $response['userinfo'] = $user_update_data;
                        return response()->json($response);
                    } else {
                        \DB::table('user_meta')->insert($user_meta_data);
                        //$otp_send=\App\OTP::SendSMSForUserRegistration($user_mobile,$pin_number);
                        $response["success"]= [
                            "statusCode"=> 202,
                            "successMessage"=> "A confirm code send your mobile",
                            "serverReferenceCode"=> $now,
                        ];
                        $response['userinfo'] = $user_update_data;
                        return response()->json($response);
                    }
                }
            } catch(\Exception $e) {
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                $response["success"]= [
                    "statusCode"=> 502,
                    "successMessage"=> "Profile updated not successfully",
                    "serverReferenceCode"=> $now,
                    'message' => $message

                ];
                return response()->json($response);
            }
        } else {
            $response["success"]= [
                "statusCode"=> 501,
                "successMessage"=> "User not found ! Please registration first.",
                "serverReferenceCode"=> $now,
            ];
            return response()->json($response);
        }
    }

    public function appChangePassword(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $userinfo = $request->input('userinfo');
        $user = \DB::table('users')
            ->where('id',$userinfo['user_id'])
            ->first();
        if(count($user) > 0) {
            $update_password=array(
                'password' => bcrypt($userinfo['password']),
                'updated_at' => $now
            );
            try {
                $update_pass=\DB::table('users')->where('id', $userinfo['user_id'])->update($update_password);
                if($update_pass) {
                    $response["success"]= [
                        "statusCode"=> 2002,
                        "successMessage"=> "Password updated not successfully",
                        "serverReferenceCode"=> $now,
                    ];
                    $response['userinfo'] = $user;
                    return response()->json($response);
                }
            } catch(\Exception $e) {
                $response["success"]= [
                    "statusCode"=> 502,
                    "successMessage"=> "Password updated not successfully!",
                    "serverReferenceCode"=> $now,
                ];
                return response()->json($response);
            }
        } else {
            $response["success"]= [
                "statusCode"=> 501,
                "successMessage"=> "User not found ! Please registration first.",
                "serverReferenceCode"=> $now,
            ];
            return response()->json($response);
        }
    }


}
