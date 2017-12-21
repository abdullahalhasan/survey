<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Classes\CustomLogger;
use App\User;

class System extends Model
{
    /**
     * Write custom access log.
     *
     * @param string $log_dir
     * @param string $file_type_name
     * @param string $message
     * @return bool
     */
    public static function CustomLogWritter($log_dir,$file_type_name,$message)
    {
        if (!file_exists(storage_path($log_dir)))
           mkdir(storage_path($log_dir), 0777, true);
        $log = new CustomLogger(storage_path($log_dir.'/'.$file_type_name));
        $log = $log->logWrite($message);
        return true;
    }

    /**
     * Write Access log for identify user ip,user id, browser etc.
     * data insert into access_log table.
     *
     * @return bool
     */
    public static function AccessLogWrite()
    {
        $page_title = \Request::route()->getName();
        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();
        $client_info  = \App\System::getBrowser();
        $client_location  = \App\System::geolocation($client_ip);
        if(\Auth::check()){
            $user_id=  \Auth::user()->id;
        } else{
            $user_id= 'user';
        }
        $access_city = isset($client_location['city']) ? $client_location['city'] : '' ;
        $access_division = isset($client_location['division']) ? $client_location['division'] : '' ;
        $access_country = isset($client_location['country']) ? $client_location['country'] : '' ;
        $uuid = \Uuid::generate(4);
        $now = date('Y-m-d H:i:s');
        $access_data = [
            'access_tran_code' => $uuid->string,
            'access_client_ip' => $client_ip,
            'access_user_id'   => $user_id,
            'access_browser'   => $client_info['browser'],
            'access_platform'  => $client_info['platform'],
            'access_city'      => $access_city,
            'access_division'  => $access_division,
            'access_country'   => $access_country,
            'access_message'   => $page_title.','.$page_url,
            'created_at'       => $now,
            'updated_at'       => $now
        ];
        \DB::table('access_log')->insert($access_data);
        $message = $client_ip.'|'.$user_id.'|'.$page_title.'|'.$page_url.'|'.$client_info['browser'].'|'.$client_info['platform'].'|'.$access_city.'|'.$access_division.'|'.$access_country;
        \App\System::CustomLogWritter("systemlog","access_log",$message);
        return true;
    }

    /**
     * Write all event of any user
     * data insert into event_log table.
     * checked auth user, if not found $user_id='guest'
     *
     * @param string $event_type.
     * @param string $event_data
     * @return Response.
     */
    public static function EventLogWrite($event_type,$event_data)
    {
        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();
        if(\Auth::check()) {
            $user_id = \Auth::user()->id;
        } else {
            $user_id = 'user';
        }
        $uuid = \Uuid::generate(4);
        $now = date('Y-m-d H:i:s');
        $event_insert = [
            'event_tran_code' => $uuid->string,
            'event_client_ip' => $client_ip,
            'event_user_id'   => $user_id,
            'event_request_url' => $page_url,
            'event_type'  => $event_type,
            'event_data'  => $event_data,
            'created_at'  => $now,
            'updated_at'  => $now
        ];
        \DB::table('event_log')->insert($event_insert);
        $message = $client_ip.'|'.$user_id.'|'.$page_url.'|'.$event_type.'|'.$event_data;
        \App\System::CustomLogWritter("eventlog","event_log",$message);
        return true;
    }

    /**
     * Write all error such as ip, user_id, error_data
     * data insert to error_log table.
     *
     * @param string $error_data
     * @return bool
     */
    public static function ErrorLogWrite($error_data)
    {
        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();
        if(\Auth::check()) {
            $user_id = \Auth::user()->id;
        } else {
            $user_id = 'user';
        }
        $uuid = \Uuid::generate(4);
        $now = date('Y-m-d H:i:s');
        $error_insert = [
            'error_tran_code' => $uuid->string,
            'error_client_ip' => $client_ip,
            'error_user_id'   => $user_id,
            'error_request_url' => $page_url,
            'error_data'  => $error_data,
            'created_at'  => $now,
            'updated_at'  => $now
        ];
        \DB::table('error_log')->insert($error_insert);
        $message = $client_ip.'|'.$user_id.'|'.$page_url.'|'.$error_data;
        \App\System::CustomLogWritter("errorlog","error_log",$message);
        return true;
    }

    /**
     * Get client IP information.
     *
     * @return array|false|string.
     */
    public static function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if(getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if(getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if(getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if(getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if(getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        if($ipaddress=='::1') {
            $ipaddress = getHostByName(getHostName());
        }
        return $ipaddress;
    }

    /**
     * Get all information about any browser.
     *
     * @return array.
     */
    public static function getBrowser()
    {
            
        $u_agent = $_SERVER['HTTP_USER_AGENT']; 
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";
        //get the platform
        if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $u_agent)){
            preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $u_agent,$matches);
            $platform = $matches[0];
        } elseif (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }else{
            $platform = 'Unknown';
        }
        // next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
            $bname = 'Internet Explorer'; 
            $ub = "MSIE"; 
        } elseif(preg_match('/Firefox/i',$u_agent)) {
            $bname = 'Mozilla Firefox'; 
            $ub = "Firefox"; 
        } elseif(preg_match('/Chrome/i',$u_agent)) {
            $bname = 'Google Chrome'; 
            $ub = "Chrome"; 
        } elseif(preg_match('/Safari/i',$u_agent)) {
            $bname = 'Apple Safari'; 
            $ub = "Safari"; 
        } elseif(preg_match('/Opera/i',$u_agent)) {
            $bname = 'Opera'; 
            $ub = "Opera"; 
        } elseif(preg_match('/Netscape/i',$u_agent)) {
            $bname = 'Netscape'; 
            $ub = "Netscape"; 
        }
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        // see how many we have
        /*$i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)) {
                $version= $matches['version'][0];
            } else {
                $version= $matches['version'][1];
            }
        } else {
            $version= $matches['version'][0];
        }*/
        // check if we have a number
        if ($version==null || $version=="") {
            $version="?";
        }
        return array(
            'userAgent' => $u_agent,
            'browser'   => $bname,
            'version'   => $version,
            'platform'  => $platform,
        );
    }

    /**
     * Get GEO location according to $ipaddress
     * use cURL method for getting ip information.
     *
     * @param string $ipaddress
     * @return array
     */
    public static function geolocation($ipaddress)
    {
        $url = "http://www.geoplugin.net/php.gp?ip=".$ipaddress;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $geo = is_array($data) ? unserialize($data) : '';
        $geolocation = array();
        if(!empty($geo)) {
            $geolocation = array(
                'ip' =>$ipaddress,
                'city'=> $geo['geoplugin_city'],
                'division' =>$geo['geoplugin_region'],
                'country' =>$geo['geoplugin_countryName'],
                'latitude' =>$geo['geoplugin_latitude'],
                'longitude'=>$geo['geoplugin_longitude']

            );
        }
        return $geolocation;
    }

    /**
     * Write log for auth information
     * check Auth user, if not found $user_email = 'guest';
     * checked auth status
     * data insert into auth_log table.
     *
     * @param string $auth_status
     * @return bool
     */
    public static function AuthLogWrite($auth_status)
    {

        $client_ip  = \App\System::get_client_ip();
        $client_ip  = \App\System::get_client_ip();
        $client_info  = \App\System::getBrowser();
        $client_location  = \App\System::geolocation($client_ip);
        if(\Auth::check()) {
            $user_id = \Auth::user()->id;
        } else {
            $user_id = 'user';
        }
        if ($auth_status==1) {
            $auth_type = "Log In";
        } else {
            $auth_type = "Log Out";
        }
        $auth_city = isset($client_location['city']) ? $client_location['city'] : '' ;
        $auth_division = isset($client_location['division']) ? $client_location['division'] : '' ;
        $auth_country = isset($client_location['country']) ? $client_location['country'] : '' ;

        $uuid = \Uuid::generate(4);
        $now = date('Y-m-d H:i:s');
        $auth_insert = [
            'auth_tran_code' => $uuid->string,
            'auth_client_ip' => $client_ip,
            'auth_user_id'   => $user_id,
            'auth_browser'   => $client_info['browser'],
            'auth_platform'  => $client_info['platform'],
            'auth_city'      => $auth_city,
            'auth_division'  => $auth_division,
            'auth_country'   => $auth_country,
            'auth_type'      => $auth_type,
            'created_at'       => $now,
            'updated_at'       => $now

        ];
        \DB::table('auth_log')->insert($auth_insert);
        $message = $client_ip.'|'.$user_id.'|'.$auth_type.'|'.$client_info['browser'].'|'.$client_info['platform'].'|'.$auth_city.'|'.$auth_division.'|'.$auth_country;
        \App\System::CustomLogWritter("authlog","auth_log",$message);
        return true;
    }
    /********************************************
    ## LogWritter
     *********************************************/

    public static function LogWriteTest($file_name,$message,$others)
    {
        if (!is_array($others)) {
            $others=array();
        }
        $view_log = new Logger('logs');
        $view_log->pushHandler(new StreamHandler(storage_path().'/logs/'.$file_name.'_'.date('Y_m_d').'.log'));
        $view_log->addRecord(200,$message,$others);
        return true;
    }
    /********************************************
    ## ConvertNumberToWords
     *********************************************/
    public static function ConvertNumberToWords($number)
    {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'ConvertNumberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . \App\System::ConvertNumberToWords(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . \App\System::ConvertNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = \App\System::ConvertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= \App\System::ConvertNumberToWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    }

    /**
     * Send mail for forget password.
     *
     * @param int $users_id
     * @param string $reset_url
     * @return true.
     */
    public static function ForgotPasswordEmail($users_id,$reset_url)
    {
        $user_info=\DB::table('users')->where('id',$users_id)->first();
        if (isset($user_info)) {
            $users_email=$user_info->email;
        } else {
            return \Redirect::back()->with('message',"Invalid User ID!");
        }
        $data['user_info'] = $user_info;
        $data['reset_url'] = $reset_url;
        $user_email = $users_email;
        $user_name = $user_info->name;
        \Mail::send('auth.forget-password-mail', $data, function($message) use ($user_email,$user_name) {
            $message->to($user_email,$user_name)->subject('Password Recovery');
        });
        return true;
    }
}
