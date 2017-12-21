@extends('layout.master')
@section('content')
    <!--SHOW ERROR MESSAGE DIV-->
    <div class="row page_row">
        <div class="col-md-12">
            @if ($errors->count() > 0 )
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
            @if (Session::has('message'))
                <div class="alert alert-success" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('message') }}
                </div>
            @endif
            @if (Session::has('errormessage'))
                <div class="alert alert-danger" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('errormessage') }}
                </div>
            @endif
        </div>
    </div>
    <!--END ERROR MESSAGE DIV-->
    <div class="row ">
        <div class="col-sm-12">
            <div class="tabbable">
                <ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
                    <li class="{{($tab=='create_user') ? 'active' : ''}}">
                        <a data-toggle="tab" href="#create_user">
                            Create User
                        </a>
                    </li>
                    <li class="{{($tab=='blocked_user') ? 'active' : ''}}">
                        <a data-toggle="tab" href="#blocked_user">
                            Blocked Users
                        </a>
                    </li>
                    <li class="{{$tab=='admins' ? 'active':''}}">
                        <a data-toggle="tab" href="#admins">
                            Admins
                        </a>
                    </li>

                    <li class="{{$tab=='employes' ? 'active':''}}">
                        <a data-toggle="tab" href="#officials">
                            Official users
                        </a>
                    </li>
                    <li class="{{$tab=='members' ? 'active':''}}">
                        <a data-toggle="tab" href="#normals">
                            Normal users
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- PANEL FOR CREATE USER -->
                    <div id="create_user" class="tab-pane {{$tab=='create_user' ? 'active':''}}">
                        <div class="row">
                            <div class="col-md-12">
                                <form  id="user-form"  action="{{url('admin/user/create')}}" method="post"
                                       enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Account Info</h3>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="firstname2" class="control-label">
                                                    Name
                                                    <span class="symbol" aria-required="true"></span>
                                                </label>
                                                <input id="first_name" type="text" placeholder="Name"
                                                       class="form-control" name="name"/>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> Gender </label>
                                                <select class="form-control" name="gender">
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="email2" class="control-label">
                                                    Email Address
                                                    <span class="symbol" aria-required="true"></span>
                                                </label>
                                                <input type="email" placeholder="email@example.com" class="form-control" name="email">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">
                                                    Mobile
                                                    <span class="symbol " aria-required="true"></span>
                                                </label>
                                                <input type="text" placeholder="User Mobile" class="form-control"
                                                       id="user_mobile" name="user_mobile"  >
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> Address </label>
                                                <input class="form-control" id="user_address" placeholder="Address" type="text" name="user_address" >
                                            </div>
                                            <div class="form-group">
                                                <label  class="control-label"> City </label>
                                                <input type="text" class="form-control" name="user_city">
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">
                                                    User Type
                                                    <span class="symbol" aria-required="true"></span>
                                                </label>
                                                <select id="user_type" class="form-control" name="user_type">
                                                    <option value="" selected="selected"> Please select user type</option>
                                                    <option value="admin"> Administrator </option>
                                                    <option value="official_user"> Official User </option>
                                                    <option value="normal_user"> Normal User </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">
                                                    User Role
                                                    <span class="symbol required" aria-required="true"></span>
                                                </label>
                                                <select id="user_role" class="form-control" name="user_role" >
                                                    <option value="" selected="selected">Please select user role</option>
                                                    <option value="admin"> Admin </option>
                                                    <option value="official_user"> Official </option>
                                                    <option value="normal_user"> Normal </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">
                                                    Password
                                                    <span class="symbol" aria-required="true"></span>
                                                </label>
                                                <input type="password" name="password" placeholder="********"
                                                       class="form-control" id="password" value="" />
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">
                                                    Confirm Password
                                                    <span class="symbol required" aria-required="true"></span>
                                                </label>
                                                <input type="password" id="confirm_password" class="form-control" name="confirm_password"
                                                       placeholder="********" value=""   />
                                            </div>
                                            <div class="form-group">
                                                <label> User Profile Image </label>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-new thumbnail profile_img_size" >
                                                        <img src="{{asset('public/assets/images/user/admin/profile.png')}}" alt="">
                                                    </div>
                                                    <div class="fileupload-preview fileupload-exists thumbnail profile_img_size"
                                                         style="line-height: 20px;">
                                                    </div>
                                                    <div class="user-edit-image-buttons">
													<span class="btn btn-light-grey btn-file">
														<span class="fileupload-new image-filechange">
                                                            <i class="fa fa-picture"></i> Select image
                                                        </span>
														<span class="fileupload-exists image-filechange">
                                                            <i class="fa fa-picture"></i> Change
                                                        </span>
														<input type="file" name="image_url" value="" />
													</span>
                                                        <a href="#" class="btn fileupload-exists btn-light-grey"
                                                           data-dismiss="fileupload">
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
                                                <label class="control-label"> Twitter </label>
                                                <span class="input-icon">
												<input class="form-control" type="text" name="user_twitter_account"
                                                       placeholder="Twitter Link">
												<i class="clip-twitter"></i>
											</span>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> Facebook </label>
                                                <span class="input-icon">
												<input class="form-control" name="user_facebook_account" type="text"
                                                       placeholder="Facebook Link">
												<i class="clip-facebook"></i>
											</span>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> Google Plus </label>
                                                <span class="input-icon">
												<input class="form-control" name="user_google_plus_account" type="text"
                                                       placeholder="Google Plus Link">
												<i class="clip-google-plus"></i>
											</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"> GitHub </label>
                                                <span class="input-icon">
												<input class="form-control" name="user_github_account" type="text"
                                                       placeholder="Github Link">
												<i class="clip-github-2"></i>
											</span>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> LinkedIn </label>
                                                <span class="input-icon">
												<input class="form-control" name="user_linkedin_account" type="text"
                                                       placeholder="Linkedin Link">
												<i class="clip-linkedin"></i>
											</span>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> Skype </label>
                                                <span class="input-icon">
												<input class="form-control" type="text" name="user_skype_account"
                                                       placeholder="Skype">
												<i class="clip-skype"></i>
											</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p>
                                                By clicking Register, you are agreeing to the Policy and Terms &amp; Conditions.
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <button class="btn btn-teal btn-block" type="submit">
                                                Register <i class="fa fa-arrow-circle-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- END PANEL FOR CREATE USER -->
                    <!-- PANEL FOR BLOCK USER -->
                    <div id="blocked_user" class="tab-pane {{$tab=='blocked_user' ? 'active':''}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="sample-table-1">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Name Slug</th>
                                            <th>User ID</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Login Sts</th>
                                            <th>User Sts</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (!empty($user_info))
                                            @foreach ($user_info as $key => $blocked_user_list)
                                                @if ($blocked_user_list->status == '-1')
                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ $blocked_user_list->name }}</td>
                                                        <td>{{ $blocked_user_list->name_slug }}</td>
                                                        <td>{{ $blocked_user_list->id }}</td>
                                                        <td>{{ $blocked_user_list->email }}</td>
                                                        <td>{{ $blocked_user_list->user_mobile }}</td>
                                                        <td>
                                                            {{ isset ($blocked_user_list->login_status)
                                                                && ($blocked_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}
                                                        </td>
                                                        <td>
                                                            {{ isset ($blocked_user_list->status)
                                                                && ($blocked_user_list->status=='active') ? 'Active' : 'Inactive'}}
                                                        </td>
                                                        <td>
                                                            @if ($blocked_user_list->status == 'active')
                                                                <button class="btn btn-primary btn-xs status col-md-12"
                                                                        data-user-id="{{$blocked_user_list->id}}"
                                                                        data-tab="blocked_user" data-status="-1">
                                                                    Block
                                                                </button>
                                                            @else
                                                                <button class="btn btn-danger btn-xs status col-md-12"
                                                                        data-user-id="{{$blocked_user_list->id}}"
                                                                        data-tab="blocked_user" data-status="1">
                                                                    Unblock
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9">
                                                    <div class="alert alert-success" role="alert">
                                                        <center><h4>No Data Available !</h4></center>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PANEL FOR BLOCK USER -->
                    <!-- PANEL FOR ADMINS -->
                    <div id="admins" class="tab-pane {{$tab=='admins' ? 'active':''}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="sample-table-1">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Name Slug</th>
                                            <th>User ID</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Login Sts</th>
                                            <th>User Sts</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (!empty($user_info))
                                            @foreach ($user_info as $key => $admin_user_list)
                                                @if (($admin_user_list->user_type == 'admin')
                                                    && ($admin_user_list->user_role == 'admin'))
                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ $admin_user_list->name }}</td>
                                                        <td>{{ $admin_user_list->name_slug }}</td>
                                                        <td>{{ $admin_user_list->id }}</td>
                                                        <td>{{ $admin_user_list->email }}</td>
                                                        <td>{{ $admin_user_list->user_mobile }}</td>
                                                        <td>
                                                            {{ isset ($admin_user_list->login_status)
                                                            && ($admin_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}
                                                        </td>
                                                        <td>
                                                            {{isset ($admin_user_list->status)
                                                                && ($admin_user_list->status=='1') ? 'Active' : 'Inactive'}}
                                                        </td>
                                                        <td>
                                                            @if ($admin_user_list->status==1)
                                                                <button class="btn btn-primary btn-xs status col-md-12"
                                                                        data-user-id="{{$admin_user_list->id}}"
                                                                        data-tab="admins" data-status="-1">
                                                                    Block
                                                                </button>
                                                            @else
                                                                <button class="btn btn-danger btn-xs status col-md-12"
                                                                        data-user-id="{{$admin_user_list->id}}"
                                                                        data-tab="admins" data-status="1">
                                                                    Unblock
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9">
                                                    <div class="alert alert-success" role="alert">
                                                        <center><h4>No Data Available !</h4></center>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PANEL FOR ADMINS -->
                    <!-- PANEL FOR EMPLOYES -->
                    <div id="officials" class="tab-pane {{$tab=='employes' ? 'active':''}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="sample-table-1">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Name Slug</th>
                                            <th>User ID</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Login Sts</th>
                                            <th>User Sts</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (!empty($user_info))
                                            @foreach ($user_info as $key1 => $employe_user_list)
                                                @if (($employe_user_list->user_type == 'admin')
                                                    && ($employe_user_list->user_role == 'employe'))
                                                    <tr>
                                                        <td>{{ $key1+1 }}</td>
                                                        <td>{{ $employe_user_list->name }}</td>
                                                        <td>{{ $employe_user_list->name_slug }}</td>
                                                        <td>{{ $employe_user_list->id }}</td>
                                                        <td>{{ $employe_user_list->email }}</td>
                                                        <td>{{ $employe_user_list->user_mobile }}</td>
                                                        <td>
                                                            {{ isset ($employe_user_list->login_status)
                                                            && ($employe_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}
                                                        </td>
                                                        <td>
                                                            {{ isset ($employe_user_list->status)
                                                            && ($employe_user_list->status=='1') ? 'Active' : 'Inactive'}}
                                                        </td>
                                                        <td>
                                                            @if ($employe_user_list->status==1)
                                                                <button class="btn btn-primary btn-xs status col-md-12"
                                                                        data-user-id="{{$blocked_user_list->id}}"
                                                                        data-tab="employes" data-status="-1">
                                                                    Block
                                                                </button>
                                                            @else
                                                                <button class="btn btn-danger btn-xs status col-md-12"
                                                                        data-user-id="{{$employe_user_list->id}}"
                                                                        data-tab="employes" data-status="1">
                                                                    Unblock
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9">
                                                    <div class="alert alert-success" role="alert">
                                                        <center><h4>No Data Available !</h4></center>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PANEL FOR EMPLOYES -->
                    <!-- PANEL FOR MEMBERS -->
                    <div id="normals" class="tab-pane {{$tab=='members' ? 'active':''}}">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover" id="sample-table-1">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Name Slug</th>
                                        <th>User ID</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Login Sts</th>
                                        <th>User Sts</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (!empty($user_info))
                                        @foreach ($user_info as $key => $member_user_list)
                                            @if (($member_user_list->user_type == 'guest')
                                                && ($member_user_list->user_role == 'member'))
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $member_user_list->name }}</td>
                                                    <td>{{ $member_user_list->name_slug }}</td>
                                                    <td>{{ $member_user_list->id }}</td>
                                                    <td>{{ $member_user_list->email }}</td>
                                                    <td>{{ $member_user_list->user_mobile }}</td>
                                                    <td>
                                                        {{ isset ($member_user_list->login_status)
                                                            && ($member_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}
                                                    </td>
                                                    <td>
                                                        {{ isset($member_user_list->status)
                                                        && ($member_user_list->status=='1') ? 'Active' : 'Inactive'}}
                                                    </td>
                                                    <td>
                                                        @if ($blocked_user_list->status==1)
                                                            <button class="btn btn-primary btn-xs status col-md-12"
                                                                    data-user-id="{{$blocked_user_list->id}}"
                                                                    data-tab="members" data-status="-1">
                                                                Block
                                                            </button>
                                                        @else
                                                            <button class="btn btn-danger btn-xs status col-md-12"
                                                                    data-user-id="{{$blocked_user_list->id}}"
                                                                    data-tab="members" data-status="1">
                                                                Unblock
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9">
                                                <div class="alert alert-success" role="alert">
                                                    <center><h4>No Data Available !</h4></center>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- END PANEL FOR MEMBERS -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptJS')
    <script>
        $(document).ready(function () {
            $('form#user-form').validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    user_mobile: {
                        required: true
                    },
                    user_type: {
                        required:true
                    },
                    user_address: {
                        required : true
                    },
                    user_city: {
                        required: true
                    },
                    password : {
                        minlength : 4,
                        required : true
                    },
                    confirm_password : {
                        required : true,
                        minlength : 4,
                        equalTo : "#password"
                    },
                    user_role: {
                        required: true
                    }
                },
                highlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>
@endsection

