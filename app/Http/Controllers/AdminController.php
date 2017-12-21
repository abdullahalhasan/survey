<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Session;
class AdminController extends Controller
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
        \App\System::AccessLogWrite();
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        return view('admin.index', $data);
    }
    /**
     * Display profile information
     * pass page title
     * Get User data by auth email
     * Get User meta data by joining user
     * Get Products by auth user.
     *
     * @return HTML view Response.
     */
    public function Profile()
    {

        $data['page_title'] = $this->page_title;

        if (isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])) {
            $tab = $_REQUEST['tab'];
        } else {
            $tab = 'panel_overview';
        }

        $data['tab'] = $tab;
        $last_login = (\Session::has('last_login')) ? \Session::get('last_login') : date('Y-m-d H:i:s');
        $data['last_login'] = \App\Common::TiemElapasedString($last_login);

        $user_info = \DB::table('users')
            ->where('email', \Auth::user()->email)
            ->first();
        $data['user_info'] = $user_info;

        $user_meta_info = \DB::table('user_meta')
            ->where('meta_user_id', \Auth::user()->id)
            ->get();

        foreach ($user_meta_info as $key => $list) {

            if ($list->user_meta_field_name == 'gender') {
                $data['gender'] = $list->user_meta_field_value;
            }
            if ($list->user_meta_field_name == 'user_city') {
                $data['user_city'] = $list->user_meta_field_value;
            }
            if ($list->user_meta_field_name == 'user_address') {
                $data['user_address'] = $list->user_meta_field_value;
            }
            if ($list->user_meta_field_name == 'user_twitter_account') {
                $data['user_twitter_account'] = $list->user_meta_field_value;
            }
            if ($list->user_meta_field_name == 'user_facebook_account') {
                $data['user_facebook_account'] = $list->user_meta_field_value;
            }
            if ($list->user_meta_field_name == 'user_google_plus_account') {
                $data['user_google_plus_account'] = $list->user_meta_field_value;
            }
            if ($list->user_meta_field_name == 'user_github_account') {
                $data['user_github_account'] = $list->user_meta_field_value;
            }
            if ($list->user_meta_field_name == 'user_linkedin_account') {
                $data['user_linkedin_account'] = $list->user_meta_field_value;
            }
            if ($list->user_meta_field_name == 'user_skype_account') {
                $data['user_skype_account'] = $list->user_meta_field_value;
            }
        }
        return view('admin.profile',$data);
    }

    /**
     * Update User Profile
     * if user meta data exist then update else insert user meta data.
     *
     * @param  Request  $request
     * @return Response
     */
    public function ProfileUpdate(Request $request)
    {
        $user_id = \Auth::user()->id;
        $v = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'user_mobile' => 'required',
        ]);
        if ($v->fails()) {
            return redirect('admin/profile')->withErrors($v)->withInput();
        }

        $now = date('Y-m-d H:i:s');
        $slug=explode(' ', strtolower($request->input('name')));
        $name_slug=implode('.', $slug);
        if (!empty(\Request::file('image_url'))) {
            $image = \Request::file('image_url');
            $img_location = $image->getRealPath();
            $img_ext = $image->getClientOriginalExtension();
            $user_profile_image = \App\Admin::UserImageUpload($img_location, $name_slug, $img_ext);
            $user_profile_image = $user_profile_image;
            $user_new_img = array(
                'user_profile_image' => $user_profile_image,
            );
            \DB::table('users')->where('id', $user_id)->update($user_new_img);
            \App\System::EventLogWrite('update, user_profile_image',json_encode($user_new_img));
        }
        $user_info_update_data = array(
            'name' => ucwords($request->input('name')),
            'name_slug' => $name_slug,
            'email' => \Request::input('email'),
            'user_mobile' => \Request::input('user_mobile'),
            'updated_at' => $now,
        );
        if(!empty($_POST)){
            $i=1;
            foreach ($_POST as $field_name => $field_value) {

                if(($field_name != '_token')
                    && ($field_name != 'name')
                    && ($field_name != 'email')
                    && ($field_name != 'user_mobile')
                    && ($field_name != 'user_profile_image'))
                {
                    $user_meta_info=\DB::table('user_meta')->where('meta_user_id', \Auth::user()->id)
                        ->where('user_meta_field_name', $field_name)
                        ->first();
                    $user_meta_update=array(
                        'meta_user_id' => \Auth::user()->id,
                        'user_meta_field_name' => $field_name,
                        'user_meta_field_value' => $field_value,
                        'updated_by' => \Auth::user()->id,
                        'updated_at' => $now,
                    );
                    if ($user_meta_info) {
                        \DB::table('user_meta')
                            ->where('meta_user_id', \Auth::user()->id)
                            ->where('user_meta_field_name', $field_name)
                            ->update($user_meta_update);
                        \App\System::EventLogWrite('update,user_meta_data_update',json_encode($user_meta_info));
                    } else {
                        $user_meta_data = array_merge(
                            $user_meta_update,
                            array(
                                'created_by' => \Auth::user()->id,
                                'created_at' => $now,
                            )
                        );
                        $user_meta_data_insert=\DB::table('user_meta')->insert($user_meta_data);
                        \App\System::EventLogWrite('insert,user_meta_data_insert',json_encode($user_meta_data_insert));
                    }
                    $i++;
                }
            }
        }
        try {
            \DB::table('users')->where('id', $user_id)->update($user_info_update_data);
            \App\System::EventLogWrite('update,users',json_encode($user_info_update_data));
            return \Redirect::to('admin/profile')->with('message',"Profile updated successfully !");
        } catch (\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return \Redirect::to('admin/profile')->with('errormessage',"Something is wrong!");
        }
    }

    /**
     * User Profile image update for specific user.
     *
     * @return Response
     */
    public function ProfileImageUpdate()
    {
        if (!empty(\Request::file('image_url'))) {
            $name_slug=\Auth::user()->name_slug;
            $image = \Request::file('image_url');
            $img_location=$image->getRealPath();
            $img_ext=$image->getClientOriginalExtension();
            $user_profile_image=\App\Admin::UserImageUpload($img_location, $name_slug, $img_ext);
            $user_profile_image=$user_profile_image;

            $user_new_img = array(
                'user_profile_image' => $user_profile_image,
            );
            try {
                \DB::table('users')->where('id', \Auth::user()->id)->update($user_new_img);
                \App\System::EventLogWrite('update,user_profile_image',json_encode($user_new_img));
                return \Redirect::to('admin/profile')->with('message',"Profile image updated successfully !");
            } catch (\Exception $e) {
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return \Redirect::to('admin/profile')->with('errormessage',"Something is wrong!");

            }
        }
    }

    /**
     * Update password for specific user
     * checked validation, if failed redirect with error message.
     *
     * @return Response.
     */
    public function UserChangePassword()
    {
        $now = date('Y-m-d H:i:s');

        $rules = array(
            'new_password' => 'required',
            'confirm_password' => 'required',
            'current_password' => 'required',
        );

        $v = \Validator::make(\Request::all(), $rules);

        if ($v->fails()) {
            return redirect('admin//profile?tab=change_password')
                ->withErrors($v)
                ->withInput();
        }

        $new_password = \Request::input('new_password');
        $confirm_password = \Request::input('confirm_password');

        if ($new_password == $confirm_password) {

            if (\Hash::check(\Request::input('current_password'), \Auth::user()->password)) {
                $update_password=array(
                    'password' => bcrypt(\Request::input('new_password')),
                    'updated_at' => $now
                );
                try {
                    \DB::table('users')->where('id', \Auth::user()->id)->update($update_password);
                    \App\System::EventLogWrite('update,users', 'password changed');
                    return \Redirect::to('admin/profile')->with('message',"Password updated successfully !");
                } catch(\Exception $e) {
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return \Redirect::to('admin/profile')->with('errormessage',"Password update failed !");
                }
            } else {
                return \Redirect::to('admin/profile?tab=change_password')
                    ->with('errormessage',"Current Password Doesn't Match !");

            }
        } else {
            return \Redirect::to('admin/profile?tab=change_password')
                ->with('message',"Password Combination Doesn't Match !");
        }
    }

    /**
     * Show the form for creating a new user
     * pass page title.
     *
     *@return HTML view Response.
     */
    public function UserManagement()
    {
        if ((\Auth::user()->user_type=="admin") && (\Auth::user()->user_role == 'admin')) {
            $data['page_title'] = $this->page_title;
            if (isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])) {
                $tab = $_REQUEST['tab'];
            } else {
                $tab = 'create_user';
            }
            $data['tab'] = $tab;
            $data['user_info'] = \DB::table('users')->get();
            return \View::make('admin.user-management',$data);
        } else {
            return \Redirect::to('admin/dashboard')->with('errormessage',"Request Wrong Url !");
        }
    }

    /**
     * Creating new User
     * insert user meta data if data input else insert null to user meta table.
     *
     * @param  Request  $request
     * @return Response
     */
    public function CreateUser(Request $request)
    {
        if ((\Auth::user()->user_type=="admin") && (\Auth::user()->user_role == 'admin')) {

            $v = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'user_mobile' => 'required',
                'user_type' => 'required',
                'user_role' => 'required',
                'password' => 'required',
                'confirm_password' => 'required',
            ]);

            if ($v->passes()) {
                if (\Request::input('password') == \Request::input('confirm_password')) {
                    $now=date('Y-m-d H:i:s');
                    $slug=explode(' ', strtolower($request->input('name')));
                    $name_slug=implode('.', $slug);
                    if (!empty(\Request::file('image_url'))) {
                        $image = \Request::file('image_url');
                        $img_location = $image->getRealPath();
                        $img_ext = $image->getClientOriginalExtension();
                        $user_profile_image = \App\Admin::UserImageUpload($img_location, $name_slug, $img_ext);
                        $user_profile_image = $user_profile_image;
                    } else {
                        $user_profile_image='';
                    }
                    $user_insert_data=array(
                        'name' => ucwords($request->input('name')),
                        'name_slug' => $name_slug,
                        'user_type' => \Request::input('user_type'),
                        'user_role' => \Request::input('user_role'),
                        'email' => \Request::input('email'),
                        'user_mobile' => \Request::input('user_mobile'),
                        'password' => bcrypt(\Request::input('password')),
                        'user_profile_image' => $user_profile_image,
                        'login_status' => 0,
                        'status' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    );
                    try {
                        \DB::table('users')->insert($user_insert_data);
                        \App\System::EventLogWrite('insert,users',json_encode($user_insert_data));
                        if(!empty($_POST)){
                            $i=1;
                            foreach ($_POST as $field_name => $field_value) {
                                if(($field_name != '_token')
                                    && ($field_name != 'name')
                                    && ($field_name != 'user_type')
                                    && ($field_name != 'user_role')
                                    && ($field_name != 'email')
                                    && ($field_name != 'password')
                                    && ($field_name != 'confirm_password')
                                    && ($field_name != 'user_mobile')
                                    && ($field_name != 'user_profile_image'))
                                {
                                    $user_info=\DB::table('users')->latest()
                                        ->where('email', \Request::input('email'))
                                        ->first();
                                    $user_meta_data=array(
                                        'meta_user_id' => $user_info->id,
                                        'user_meta_field_name' => $field_name,
                                        'user_meta_field_value' => $field_value,
                                        'created_by' => \Auth::user()->id,
                                        'updated_by' => \Auth::user()->id,
                                        'created_at' => $now,
                                        'updated_at' => $now,
                                    );
                                    \DB::table('user_meta')->insert($user_meta_data);
                                    $i++;
                                }
                            }
                        }
                        return \Redirect::to('admin/user/management')
                            ->with('message',"User Account Created Successfully !");
                    } catch(\Exception $e) {
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::to('admin/user/management')->with('message',"User Already Exist !");
                    }
                } else {
                    return \Redirect::to('admin/user/management')->with('message',"Password Does Not Matched !");
                }
            } else {
                return \Redirect::to('admin/user/management')->withInput()->withErrors($v->messages());
            }
        } else {
            return \Redirect::to('admin/dashboard')->with('message',"Request Wrong Url !");
        }
    }

    /**
     * Change status for individual user.
     *
     * @param int $user_id
     * @param int $status.
     *
     * @return Response.
     */
    public function ChangeUserStatus($user_id, $status)
    {
        if ((\Auth::user()->user_type=="admin") && (\Auth::user()->user_role == 'admin')) {
            $now = date('Y-m-d H:i:s');
            if (!empty($user_id) && !empty($status)) {

                $update_data=array(
                    'status' => $status,
                    'updated_at' => $now,
                );
                \DB::table('users')->where('id', $user_id)->update($update_data);
                return 1;
            }
        } else {
            return \Redirect::to('admin/dashboard')->with('message',"Request Wrong Url !");
        }
    }


}
