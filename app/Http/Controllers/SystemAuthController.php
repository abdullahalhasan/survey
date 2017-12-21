<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Message\Response;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\System;
use App\Email;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class SystemAuthController extends Controller
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
        \App\System::AccessLogWrite();
    }

    /**
     * Show admin login page for admin
     * checked Auth user, if failed get user data according to email.
     * checked user type, if "admin" redirect to dashboard
     * or redirect to login.
     *
     * @return HTML view Response.
     */
    public function authLogin()
    {
        if (\Auth::check()) {
            if (!empty(\Auth::user()->user_type)) {
                if (\Auth::user()->user_type=="admin") {
                    \App\User::LogInStatusUpdate("login");
                    return redirect('admin/dashboard');
                } else if (\Auth::user()->user_type=="official_user") {
                    \App\User::LogInStatusUpdate("login");
                    return redirect('official/user/dashboard');
                } else if (\Auth::user()->user_type=="normal_user") {
                    \App\User::LogInStatusUpdate("login");
                    return redirect('normal/user/dashboard');
                } else {
                    \App\User::LogInStatusUpdate("login");
                    return redirect('auth/login');
                }
            } else {
                \Auth::logout();
                return redirect('auth/login')->with('errormessage', 'Whoops, looks like something went wrong!.');
            }
        } else {
            $data['page_title'] = $this->page_title;
            $session_email=\Session::get('email');
            if (!empty($session_email)) {
                $user_info=\DB::table('users')
                    ->where('email', $session_email)
                    ->select('email','name','user_profile_image')
                    ->first();
                $data['user_info']=$user_info;
            }
            return view('auth.sign',$data);
        }
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
    public function authPostLogin(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $remember = ($request->has('remember')) ? true : false;
        $credentials = [
            'email' => $request->input('email'),
            'password'=>$request->input('password'),
            'status'=> "active"
        ];

        if (\Auth::attempt($credentials)) {
            \Session::put('email', \Auth::user()->email);
            \Session::put('last_login', Auth::user()->last_login);
            if (\Session::has('pre_login_url') ) {
                $url = \Session::get('pre_login_url');
                \Session::forget('pre_login_url');
                return redirect($url);
            } else if(\Auth::user()->user_type=="admin") {
                \App\User::LogInStatusUpdate("login");
                return redirect('admin/dashboard');
            } else if (\Auth::user()->user_type=="official_user") {
                \App\User::LogInStatusUpdate("login");
                return redirect('official/user/dashboard');
            } else {
                \App\User::LogInStatusUpdate("logout");
                \Auth::logout();
                return redirect('auth/login')
                    ->with('errormessage',"Sorry, You don't have permission to access this page.");
            }
        } else {
            return redirect('auth/login')
                ->with('errormessage',"Incorrect combinations.Please try again.");
        }
    }

    /**
     * Admin logout
     * check auth login, if failed redirect with error message
     * get user data according to email
     * checked name slug, if found change login status and logout user.
     *
     * @param string $name_slug
     * @return Response.
     */
    public function authLogout($name_slug)
    {
        if (\Auth::check()) {
            $user_info = \App\User::where('email',\Auth::user()->email)->first();
            if (!empty($user_info) && ($name_slug==$user_info->name_slug)) {
                \App\User::LogInStatusUpdate("logout");
                \Auth::logout();
               //\Session::flush();
               /* \Session::forget('latest_product_info');
                \Session::forget('shop_product_basic_data');
                \Session::forget('shop_product_price_data');
                \Session::forget('shop_product_description_data');
                \Session::forget('shop_product_image_data');*/
                return \Redirect::to('auth/login');
            } else {
                return \Redirect::to('auth/login');
            }
        } else {
            return \Redirect::to('auth/login')->with('errormessage',"Error logout");
        }
    }

    /**
     * User Registration
     * checked validation, if failed redirect with message
     * data store into users table.
     *
     * @param Request $request
     * @return Response
     */
    public function authRegistration(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'repeat_password' => 'required|in_array:password',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $slug=explode(' ', strtolower($request->input('name')));
        $name_slug=implode('.', $slug);
        $registration=array(
            'name' => ucwords($request->input('name')),
            'name_slug' => $name_slug,
            'user_type' => 'normal_user',
            'user_role' => 'normal_user',
            'user_profile_image' => '',
            'login_status' => 0,
            'status' => 'active',
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'created_at' => $now,
            'updated_at' => $now,
        );
        try {
            $registration = \DB::table('users')->insert($registration);
            if ($registration) {
                \App\System::EventLogWrite('insert,users',json_encode($registration));
                return redirect('auth/login')->with('message',"You have successfully registered");
            }
        } catch(\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect('auth/login')->with('errormessage',"Duplicate email or something is wrong on user registration ! Please try again..");
        }
    }

    /**
     * Send mail to user who forget his account password
     * check user name exist, if not found redirect to same page.
     *
     * @param  $request
     * @return Response.
     */
    public function authForgotPasswordConfirm(Request $request)
    {
        $v = \Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $email = $request->input('email');
        $user_email= \DB::table('users')->where('email','=',$email)->first();
        if (!$user_email) {
            return redirect('auth/login?box=forgot')->with('errormessage',"Sorry email does not match!");
        }
        $users_email=$user_email->email;
        $users_id=$user_email->id;
        \Cookie::queue('petp_reset_password_email', $users_email, 60,true);
        $reset_url= url('auth/forget/password/'.$users_id.'/verify').'?token='.$user_email->remember_token;
        \App\System::ForgotPasswordEmail($users_id, $reset_url);
        return redirect('auth/login')->with('message',"Please check your mail !.");
        \Cookie::queue('petp_reset_password_email', null, 60,true);
    }

    /**
     * creating form for new password
     * update password according to user_id.
     *
     * @param int $users_id
     * @return HTML view Response.
     */
    public function authSystemForgotPasswordVerification($user_id)
    {
        $remember_token=$_GET['token'];
        $user_serial_no= \DB::table('users')->where('id','=',$user_id)->first();
        $data['user_serial_no']=$user_serial_no;
        $data['remember_token']=$remember_token;
        $data['page_title'] = $this->page_title;
        return \View::make('auth.set-new-password',$data);
    }

    /**
     * Verification reset password according to user email.
     * get user data according to $email.
     *
     * @param string $email
     * @return HTML view Response.
     */
    public function ResetPasswordVerification($email) {
        $remember_token=$_GET['token'];
        $user_serial_no= \DB::table('users')->where('email','=',$email)->first();
        $data['user_serial_no']=$user_serial_no;
        $data['remember_token']=$remember_token;
        $data['page_title'] = $this->page_title;
        return \View::make('pages.forgot.new-password',$data);
    }

    /**
     * Set new password according to user
     * check validation, if failed redirect same page with error message
     * change user password and update user table.
     *
     * @param Request $request
     * @return Response.
     */
    public function authSystemNewPasswordPost(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $validator = \Validator::make($request->all(), [
            'password' => 'required',
            'repeat_password' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $new_password = $request->input('password');
        $repeat_password = $request->input('repeat_password');
        $user_id =  \Request::input('user_id');
        if ($new_password == $repeat_password) {
            $update_password=array(
                'password' => bcrypt($request->input('password')),
                'updated_at' => $now
            );
            try {
                $update_pass=\DB::table('users')->where('id', $user_id)->update($update_password);
                if($update_pass) {
                    \App\System::EventLogWrite('update,users', 'password changed');
                    return redirect('auth/login')->with('message',"Password updated successfully !");
                }
            } catch(\Exception $e) {
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return redirect('auth/login')->with('errormessage',"Password update failed  !");
            }
        } else {
            return redirect()->back()->with('message',"Password Combination Doesn't Match !");
        }
    }
}