<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Classes\CustomLogger;

class Api extends Model
{

    protected $android_key='AIzaSyCmuZv9fJcr-REVdmAuRCQjMrFfu2HuuPY';

    /********************************************
    ## RequestLogWrite
     *********************************************/
    public static function RequestLogWrite($inputs){

        $page_title = \Request::route()->getName();
        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();
        $client_info  = \App\System::getBrowser();
        $client_location  = \App\System::geolocation($client_ip);

        if(\Auth::check()){
            $user_id=  \Auth::user()->id;
        }else
            $user_id= 'guest';


        $request_city = isset($client_location['city']) ? $client_location['city'] : '' ;
        $request_division = isset($client_location['division']) ? $client_location['division'] : '' ;
        $request_country = isset($client_location['country']) ? $client_location['country'] : '' ;


        $now = date('Y-m-d H:i:s');
        $request_data = [
            'request_client_ip' => $client_ip,
            'request_user_id'   => $user_id,
            'request_browser'   => $client_info['browser'],
            'request_platform'  => $client_info['platform'],
            'request_city'      => $request_city,
            'request_division'  => $request_division,
            'request_country'   => $request_country,
            'request_url'		=> $page_url,
            'request_message'   => json_encode($inputs),
            'created_at'       => $now,
            'updated_at'       => $now

        ];

        $request_id=\DB::table('request_log')->insertGetId($request_data);


        /***********Text Log**************************/

        $message = $client_ip.'|'.$user_id.'|'.$page_title.'|'.$page_url.'| '.json_encode($inputs).' |'.$client_info['browser'].'|'.$client_info['platform'].'|'.$request_city.'|'.$request_division.'|'.$request_country;

        \App\System::CustomLogWritter("requestlog","request_log",$message);

        return $request_id;

    }



    /********************************************
    ## ResponseLogWrite
     *********************************************/
    public static function ResponseLogWrite($response_type,$response_data){

        $client_ip  = \App\System::get_client_ip();
        $page_url   = \Request::fullUrl();


        if(\Auth::check())
            $user_id = \Auth::user()->id;
        else
            $user_id = 'guest';

        $now = date('Y-m-d H:i:s');

        $response_insert = [

            'response_client_ip' => $client_ip,
            'response_user_id'   => $user_id,
            'response_request_url' => $page_url,
            'response_type'  => $response_type,
            'response_data'  => $response_data,
            'created_at'  => $now,
            'updated_at'  => $now

        ];

        \DB::table('response_log')->insert($response_insert);


        /***********Text Log**************************/

        $message = $client_ip.'|'.$user_id.'|'.$page_url.'|'.$response_type.'|'.$response_data;

        \App\System::CustomLogWritter("responselog","response_log",$message);

        return true;



    }


    /********************************************
    ## IMEIChecker
     *********************************************/
    public static function IMEIChecker($imei_no){

        /*$validation = \App\Api::ValidateIMEI($imei_no);

        if($validation==1)
            return $imei_no;
        else
            return false;*/

        return $imei_no;
    }

    /********************************************
    ## ValidateIMEI
     *********************************************/
    public static function ValidateIMEI($imei, $use_checksum = true){

        if (is_string($imei)) {
            if (preg_match('/^[1-9][0-9]*$/', $imei)) {
                if (!$use_checksum) return true;
                for ($i = 0, $sum = 0; $i < 14; $i++) {
                    $tmp = $imei[$i] * (($i%2) + 1 );
                    $sum += ($tmp%10) + intval($tmp/10);
                }
                return (((10 - ($sum%10)) %10) == $imei[14]);
            }
        }
        return 0;
    }

    /********************************************
    ## AppKeyChecker
     *********************************************/
    public static function AppKeyChecker($app_key){

        /*$key_values = (!empty($app_key) && strlen($app_key)==14)? explode('-',$app_key):'';

        if(count($key_values)==3 ){
            $check = isset($key_values[1]) && ($key_values[1]=='ltech')? 1:0;

            if($check==1)
                return $app_key;
            else return false;

        }else return false;*/

        return $app_key;
    }


    /********************************************
    ## TypeIdInfo
     *********************************************/
    public static function TypeIdInfo($info_type,$type_id){

        if($info_type=='wish_list'){
            $coupon_info = \DB::table('tbl_coupon')->where('coupon_id',$type_id)->first();
            return $coupon_info;
        }else if($info_type=='follow'){
            $merchant_info = \DB::table('tbl_merchant')->where('merchant_id',$type_id)->first();
            return $merchant_info;
        }else return false;
    }



    /********************************************
    ## SendMessagePush
     *********************************************/
    public static function SendMessagePush($message, $select_type, $merchant_name, $merchant_logo, $branch_address, $coupon_id, $coupon_discount_rate, $push_token, $platform_type){

        $request_data['to']=$push_token;
        $request_data['notification'] = array(
            "type" => "coupon",
            "title" =>$merchant_name.', Discount Rate : '.$coupon_discount_rate,
            "merchant_logo" => "http://yess.com.bd/assets/images/merchant/small-icon/".$merchant_logo,
            "body" => $message,
            "click_action" => "NotificationViewActivity"
        );
        $request_data['data'] = array(
            "type" => "coupon",
            "title" => $merchant_name.', Discount : '.$coupon_discount_rate.'%',
            "merchant_logo" => "http://yess.com.bd/assets/images/merchant/small-icon/".$merchant_logo,
            "message" => $message,
            "coupon_id" => $coupon_id,
        );

        $fields = json_encode($request_data);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");

        if($platform_type == 'android'){
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;',
                'Authorization: key=AIzaSyCmuZv9fJcr-REVdmAuRCQjMrFfu2HuuPY'));

        }elseif($platform_type == 'ios'){
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;',
                'Authorization: key=AIzaSyAO9Cw3OLUnUBags-upljxQWXmV5lBYhYw'));
        }


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;

    }


    /********************************************
    ## SendEventMessagePush
     *********************************************/
    public static function SendEventMessagePush($title, $message, $merchant_id, $merchant_logo, $push_token, $platform_type){

        $request_data['to'] =$push_token;

        $request_data['notification'] = array(
            "type" => "event",
            "merchant_logo"=>"http://yess.com.bd/assets/images/merchant/small-icon/".$merchant_logo,
            "title" => $title,
            "body" => $message,
            "click_action" => "NotificationViewActivity"
        );
        $request_data['data'] = array(
            "type" => "event",
            "merchant_logo"=>"http://yess.com.bd/assets/images/merchant/small-icon/".$merchant_logo,
            "title" => $title,
            "message" => $message,
            "merchant_id" => $merchant_id,
        );

        $fields = json_encode($request_data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");

        if($platform_type == 'android'){

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;',
                'Authorization: key=AIzaSyCmuZv9fJcr-REVdmAuRCQjMrFfu2HuuPY'));
        }elseif($platform_type == 'ios'){

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;',
                'Authorization: key=AIzaSyAO9Cw3OLUnUBags-upljxQWXmV5lBYhYw'));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;

    }


    /********************************************
    ## SendMessagePushAllApp
     *********************************************/
    public static function SendMessagePushAllApp($message, $merchant_name, $merchant_logo, $coupon_id, $coupon_discount_rate, $select_type){


        if($select_type == 'android'){

            $request_data['to'] ='/topics/global';

            $request_data['data'] = array(
                "type" => "coupon",
                "merchant_logo" => "http://yess.com.bd/assets/images/merchant/small-icon/".$merchant_logo,
                "title" => $merchant_name.', Discount : '.$coupon_discount_rate.'%',
                "message" => $message,
                "coupon_id" => $coupon_id,
            );

            $fields = json_encode($request_data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");


            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;',
                'Authorization: key=AIzaSyCmuZv9fJcr-REVdmAuRCQjMrFfu2HuuPY'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

        }elseif($select_type == 'ios'){

            $request_data['to'] ='/topics/global';


            $request_data['notification'] = array(
                "type" => "coupon",
                "merchant_logo" => "http://yess.com.bd/assets/images/merchant/small-icon/".$merchant_logo,
                "title" => $merchant_name.', Discount : '.$coupon_discount_rate.'%',
                "body" => $message,
                "coupon_id" => $coupon_id,
                "click_action" => "NotificationViewActivity"
            );

            $request_data['data'] = array(
                "type" => "coupon",
                "merchant_logo" => "http://yess.com.bd/assets/images/merchant/small-icon/".$merchant_logo,
                "title" => $merchant_name.', Discount : '.$coupon_discount_rate.'%',
                "message" => $message,
                "coupon_id" => $coupon_id,
            );

            $fields = json_encode($request_data);
            // print("\nJSON sent:\n");
            // print($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;',
                'Authorization: key=AIzaSyAO9Cw3OLUnUBags-upljxQWXmV5lBYhYw'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }



    }


    /********************************************
    ## SendGreetingsMessagePush
     *********************************************/
    public static function SendGreetingsMessagePush($title, $message, $select_type){


        if($select_type == 'android'){

            $request_data['to'] ='/topics/global';

            $request_data['data'] = array(
                "type"  => "message",
                "title" => $title,
                "message" => $message,
            );

            $fields = json_encode($request_data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");


            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;',
                'Authorization: key=AIzaSyCmuZv9fJcr-REVdmAuRCQjMrFfu2HuuPY'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

        }elseif($select_type == 'ios'){

            $request_data['to'] ='/topics/global';

            $request_data['notification'] = array(
                "type"  => "message",
                "title" => $title,
                "message" => $message,
            );
            $request_data['data'] = array(
                "type"  => "message",
                "title" => $title,
                "message" => $message,
            );

            $fields = json_encode($request_data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");


            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;',
                'Authorization: key=AIzaSyAO9Cw3OLUnUBags-upljxQWXmV5lBYhYw'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }



    }
}
