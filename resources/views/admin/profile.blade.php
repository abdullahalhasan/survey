@extends('layout.master')
@section('content')
    <!--ERROR MESSAGE-->
    <div class="row ">
        <div class="col-md-12">
            @if($errors->count() > 0 )
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <h6>The following errors have occurred:</h6>
                    <ul>
                        @foreach( $errors->all() as $message )
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(Session::has('message'))
                <div class="alert alert-success" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('message') }}
                </div>
            @endif
            @if(Session::has('errormessage'))
                <div class="alert alert-danger" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('errormessage') }}
                </div>
            @endif
        </div>
    </div>
    <!--END ERROR MESSAGE-->
    <!--PAGE CONTENT -->
    <div class="row ">
        <div class="col-sm-12">
            <div class="tabbable">
                <ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
                    <li class="{{isset($tab) && ($tab=='panel_overview') ? 'active' : ''}}">
                        <a data-toggle="tab" href="#panel_overview">
                            Overview
                        </a>
                    </li>
                    <li class="{{isset($tab) && ($tab=='panel_edit_account') ? 'active' : ''}}">
                        <a data-toggle="tab" href="#panel_edit_account">
                            Update Account
                        </a>
                    </li>
                    <li class="{{isset($tab) && ($tab=='messages') ? 'active' : ''}}">
                        <a data-toggle="tab" href="#messages">
                            Messages
                        </a>
                    </li>
                    <li class="{{isset($tab) && ($tab=='notifications') ? 'active' : ''}}">
                        <a data-toggle="tab" href="#notifications">
                            Notifications
                        </a>
                    </li>
                    <li class="{{isset($tab) && ($tab=='change_password') ? 'active' : ''}}">
                        <a data-toggle="tab" href="#change_password">
                            Change Password
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- PANEL FOR OVERVIEW-->
                    <div id="panel_overview" class="tab-pane in {{isset($tab) && ($tab=='panel_overview') ? 'active' : ''}}">
                        <div class="row">
                            <div class="col-sm-5 col-md-4">
                                <div class="user-left">
                                    <div class="center">
                                        <h4>{{isset($user_info->name) ? $user_info->name : ''}}</h4>
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <form action="{{url('admin/profile/image/update')}}" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}" >
                                                <div class="user-image">
                                                    <div class="fileupload-new thumbnail profile_img_size">
                                                        @if(!empty($user_info->user_profile_image))
                                                            <img src="{{asset('public/assets/images/user/admin/'.$user_info->user_profile_image)}}" alt="User Profile Photo">
                                                        @else
                                                            <img src="{{asset('public/assets/images/user/admin/profile.png')}}" alt="User Profile Photo">
                                                        @endif
                                                    </div>
                                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                    <div class="user-image-buttons">
                                                        <span class="btn btn-teal btn-file btn-sm">
                                                            <span class="fileupload-new">
                                                                <i class="fa fa-pencil"></i>
                                                            </span>
                                                            <span class="fileupload-exists">
                                                                <i class="fa fa-pencil"></i>
                                                            </span>
                                                            <input type="file" name="image_url" value="">
                                                        </span>
                                                        <button type="submit" class="btn fileupload-exists btn-primary btn-sm">Save</button>
                                                        <a href="#" class="btn fileupload-exists btn-bricky btn-sm" data-dismiss="fileupload">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <hr>
                                        <p>
                                            <a class="btn btn-twitter btn-sm btn-squared" href="{{isset($user_twitter_account) ? $user_twitter_account : '#'}}" target="blank">
                                                <i class="fa fa-twitter"></i>
                                            </a>
                                            <a class="btn btn-linkedin btn-sm btn-squared" href="{{isset($user_linkedin_account) ? $user_linkedin_account : '#'}}" target="blank">
                                                <i class="fa fa-linkedin"></i>
                                            </a>
                                            <a class="btn btn-google-plus btn-sm btn-squared" href="{{isset($user_google_plus_account) ? $user_google_plus_account : '#'}}" target="blank">
                                                <i class="fa fa-google-plus"></i>
                                            </a>
                                            <a class="btn btn-linkedin btn-sm btn-squared" href="{{isset($user_facebook_account) ? $user_facebook_account : '#'}}" target="blank">
                                                <i class="fa fa-facebook"></i>
                                            </a>
                                        </p>
                                        <hr>
                                    </div>
                                    <table class="table table-condensed table-hover">
                                        <thead>
                                        <tr>
                                            <th colspan="3">Contact Information</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>name:</td>
                                            <td>
                                                <a href="">
                                                    {{isset($user_info->name) ? strtolower($user_info->name) : ''}}
                                                </a></td>
                                            <td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                        </tr>

                                        <tr>
                                            <td>email:</td>
                                            <td>
                                                <a href="">
                                                    {{isset($user_info->email) ? $user_info->email : ''}}
                                                </a></td>
                                            <td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>mobile:</td>
                                            <td>{{isset($user_info->user_mobile) ? $user_info->	user_mobile : ''}}</td>
                                            <td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
                                        </tr>

                                        </tbody>
                                    </table>
                                    <table class="table table-condensed table-hover">
                                        <thead>
                                        <tr>
                                            <th colspan="3">General information</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Position</td>
                                            <td>{{isset($user_info->user_type) ? ucfirst($user_info->user_type) : ''}}</td>
                                            <td><a href="" class="show-tab"></a></td>
                                        </tr>
                                        <tr>
                                            <td>Last Logged In</td>
                                            <td> {{ $last_login  }}</td>
                                            <td><a href="" class="show-tab"></a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                </div>
                            </div>
                            <div class="col-sm-7 col-md-8">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <a href="{{url('admin/dashboard/profile?tab=messages')}}" style="text-decoration:none">
                                            <button class="btn btn-icon btn-block pulsate">
                                                <i class="clip-bubble-2"></i>
                                                Messages <span class="badge badge-info"> 23 </span>
                                            </button>
                                        </a>
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="{{url('admin/dashboard/profile?tab=notifications')}}" style="text-decoration:none">
                                            <button class="btn btn-icon btn-block">
                                                <i class="clip-list-3"></i>
                                                Notifications <span class="badge badge-info"> 9 </span>
                                            </button>
                                        </a>
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="{{url('/dashboard/profile?tab=change_password')}}" style="text-decoration:none">
                                            <button class="btn btn-icon btn-block">
                                                <i class="clip-user-2"></i>
                                                Change Password
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--END PANEL FOR OVERVIEW -->
                    <!-- PANEL FOR EDIT ACCOUNT -->
                    <div id="panel_edit_account" class="tab-pane in {{isset($tab) && ($tab=='panel_edit_account') ? 'active' : ''}}">
                        <form action="{{url('admin/profile/update')}}" method="post" enctype="multipart/form-data" role="form" id="form">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>Account Info</h3>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">
                                            Name
                                            <span class="symbol required" aria-required="true"></span>
                                        </label>
                                        <input type="text" required placeholder="Name" class="form-control" id="firstname"
                                               name="name" value="{{isset($user_info->name) ? $user_info->name : ''}}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            Gender
                                        </label>
                                        <select class="form-control" name="gender">
                                            <option {{(isset($gender) && ($gender=='male')) ? 'selected' : ''}} value="male">Male</option>
                                            <option {{(isset($gender) && ($gender=='female')) ? 'selected' : ''}}  value="female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            Email Address
                                            <span class="symbol required" aria-required="true"></span>
                                        </label>
                                        <input type="email" required placeholder="email@example.com" class="form-control" id="email" name="email" value="{{isset($user_info->email) ? $user_info->email : ''}}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            Mobile
                                            <span class="symbol required" aria-required="true"></span>
                                        </label>
                                        <input type="text" required placeholder="User Mobile" class="form-control" id="phone" name="user_mobile" value="{{isset($user_info->user_mobile) ? $user_info->user_mobile : ''}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">
                                                    City
                                                </label>
                                                <input class="form-control" placeholder="City" type="text"  name="user_city" id="city" value="{{isset($user_city) ? $user_city : ''}}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label">
                                                    Address
                                                </label>
                                                <input class="form-control" placeholder="Address" type="text" name="user_address" id="zipcode" value="{{isset($user_address) ? $user_address : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            Image Upload
                                        </label>
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail profile_img_size" >
                                                @if (!empty($user_info->user_profile_image))
                                                    <img src="{{asset('public/assets/images/user/admin/'.$user_info->user_profile_image)}}" alt="User Profile Photo">
                                                @else
                                                    <img src="{{asset('public/assets/images/user/admin/profile.png')}}" alt="User Profile Photo">
                                                @endif
                                            </div>
                                            <div class="fileupload-preview fileupload-exists thumbnail profile_img_size" style="line-height: 20px;"></div>
                                            <div class="user-edit-image-buttons">
                                                <span class="btn btn-light-grey btn-file">
                                                <span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span>
                                                    <span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
                                                    <input type="file" name="image_url" value="" /></span>
                                                    <a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
                                                        <i class="fa fa-times"></i> Remove
                                                    </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>Additional Info</h3>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">
                                            Twitter
                                        </label>
                                        <span class="input-icon">
                                            <input class="form-control" type="text" name="user_twitter_account" placeholder="Twitter Link" value="{{isset($user_twitter_account) ? $user_twitter_account : ''}}"><i class="clip-twitter"></i>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Facebook</label>
                                        <span class="input-icon">
                                            <input class="form-control" name="user_facebook_account" type="text" placeholder="Facebook Link" value="{{isset($user_facebook_account) ? $user_facebook_account : ''}}"><i class="clip-facebook"></i>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Google Plus</label>
                                        <span class="input-icon">
                                            <input class="form-control" name="user_google_plus_account" type="text" placeholder="Google Plus Link" value="{{isset($user_google_plus_account) ? $user_google_plus_account : ''}}"><i class="clip-google-plus"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Github</label>
                                        <span class="input-icon">
                                            <input class="form-control" name="user_github_account" type="text" placeholder="Github Link" value="{{isset($user_github_account) ? $user_github_account : ''}}"><i class="clip-github-2"></i>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Linkedin</label>
                                        <span class="input-icon">
                                            <input class="form-control" name="user_linkedin_account" type="text" placeholder="Linkedin Link" value="{{isset($user_linkedin_account) ? $user_linkedin_account : ''}}"><i class="clip-linkedin"></i>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Skype</label>
                                        <span class="input-icon">
                                            <input class="form-control" type="text" name="user_skype_account" placeholder="Skype" value="{{isset($user_skype_account) ? $user_skype_account : ''}}"><i class="clip-skype"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    <p>
                                        By clicking UPDATE, you are agreeing to the Policy and Terms &amp; Conditions.
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <button class="btn btn-teal btn-block" type="submit">
                                        Update <i class="fa fa-arrow-circle-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- END PANEL FOR EDIT ACCOUNT -->

                    <!-- PANEL FOR MESSAGES -->
                    <div id="messages" class="tab-pane in {{isset($tab) && ($tab=='messages') ? 'active' : ''}}">
                        <div class="row">
                        </div>
                    </div>
                    <!-- END PANEL FOR MESSAGES -->
                    <!-- PANEL FOR NOTIFICATION -->
                    <div id="notifications" class="tab-pane in {{isset($tab) && ($tab=='notifications') ? 'active' : ''}}">
                        <div class="row">

                        </div>
                    </div>
                    <!-- END PANEL FOR NOTIFICATION -->
                    <!-- PANEL FOR CHANGE PASSWORD -->
                    <div id="change_password" class="tab-pane in {{isset($tab) && ($tab=='change_password') ? 'active' : ''}}">
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 change_password">
                                <div class="col-md-4">
                                    @if (!empty($user_info->user_profile_image))
                                        <img src="{{asset('public/assets/images/user/admin/'.$user_info->user_profile_image)}}" alt="User Profile Photo">
                                    @else
                                        <img src="{{asset('public/assets/images/user/admin/profile.png')}}" alt="User Profile Photo">
                                    @endif
                                </div>
                                <div class="col-md-8 info"><h1><i class="fa fa-lock"></i> {{isset($user_info->name) ? $user_info->name : ''}}</h1>
                                    <form action="{{url('admin/change/password')}}" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}" >
                                        <div class="row">
                                            <div class="col-md-6" style="padding-right:0">
                                                <span><i>New Password</i></span>
                                                <input type="password" name="new_password" placeholder="New Password" class="form-control" value="">
                                            </div>
                                            <div class="col-md-6">
                                                <span><i>Confirm Password</i></span>
                                                <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="input-group" style="margin-top:7px">
                                            <input type="password" name="current_password" placeholder="Current Password" class="form-control" value="">
                                            <span class="input-group-btn">
												<button class="btn btn-blue" type="submit">
													<i class="fa fa-chevron-right"></i>
												</button>
											</span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                    <!-- END PANEL FOR CHANGE PASSWORD -->
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--END PAGE CONTENT-->
@endsection