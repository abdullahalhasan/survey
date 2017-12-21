<div class="main-navigation navbar-collapse collapse">
    <!-- start: MAIN MENU TOGGLER BUTTON -->
    <div class="navigation-toggler">
        <i class="clip-chevron-left"></i>
        <i class="clip-chevron-right"></i>
    </div>
    <!-- end: MAIN MENU TOGGLER BUTTON -->
    <!-- start: MAIN NAVIGATION MENU -->
    <ul class="main-navigation-menu">
        @if(\Auth::user()->user_role=="admin")
            <li class="{{isset($page_title) && ($page_title=='Admin Dashboard') ? 'active' : ''}} ">
                <a href="{{url('admin/dashboard')}}"><i class="clip-home-3"></i>
                    <span class="title"> Dashboard </span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="{{isset($page_title) && ($page_title=='Profile') ? 'active' : ''}} ">
                <a href="{{url('admin/profile')}}"><i class="clip-user-2"></i>
                    <span class="title"> My Profile </span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="{{isset($page_title) && ($page_title=='Company') ? 'active' : '' ||
                    isset($page_title) && ($page_title=='Campaign Category') ? 'active' : ''}}">
                <a href="javascript:void (0)"><i class="clip-settings" aria-hidden="true"></i>
                    <span class="title"> Setting </span><i class="icon-arrow"></i>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu">
                    <li class="{{isset($page_title) && ($page_title=='Company') ? 'active' : ''}}">
                        <a href="{{url('admin/company')}}">
                            <i class="fa fa-institution" aria-hidden="true"></i>
                            <span class="title"> Company </span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="{{isset($page_title) && ($page_title=='Campaign Category') ? 'active' : ''}}">
                        <a href="{{url('admin/campaign/category')}}">
                            <i class="clip-brightness-high" aria-hidden="true"></i>
                            <span class="title">Campaign Category</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{{isset($page_title) && ($page_title=='Survey Campaign') ? 'active' : '' ||
                    isset($page_title) && ($page_title=='Question') ? 'active' : ''}}">
                <a href="javascript:void (0)"><i class="fa fa-cogs" aria-hidden="true"></i>
                    <span class="title"> Setup </span><i class="icon-arrow"></i>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu">
                    <li class="{{isset($page_title) && ($page_title=='Survey Campaign') ? 'active' : ''}}">
                        <a href="{{url('admin/survey/campaign')}}">
                            <i class="fa fa-life-saver" aria-hidden="true"></i>
                            <span class="title"> Survey Campaign </span>
                            <span class="selected"></span>
                        </a>
                    </li>
                   {{-- <li class="{{isset($page_title) && ($page_title=='Question') ? 'active' : ''}}">
                        <a href="{{url('admin/question')}}">
                            <i class="fa fa-question" aria-hidden="true"></i>
                            <span class="title">Question</span>
                        </a>
                    </li>--}}
                </ul>
            </li>
            <li class="{{isset($page_title) && ($page_title=='User Management') ? 'active' : ''}} ">
                <a href="javascript:void(0)"><i class="clip-user-plus"></i>
                    <span class="title"> User Management </span><i class="icon-arrow"></i>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{url('admin/user/management?tab=create_user')}}">
                            <span class="title"> Create User </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/user/management?tab=blocked_user')}}">
                            <span class="title"> Blocked User </span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:;">
                            User List <i class="icon-arrow"></i>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{url('admin/user/management?tab=admins')}}">
                                    Admins
                                </a>
                            </li>
                            <li>
                                <a href="{{url('admin/user/management?tab=employes')}}">
                                    Employes
                                </a>
                            </li>
                            <li>
                                <a href="{{url('admin/user/management?tab=members')}}">
                                    Members
                                </a>
                            </li>
                            <li>
                                <a href="{{url('admin/user/management?tab=guests')}}">
                                    Guests
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        @elseif(\Auth::user()->user_role=='official_user')
            <li class="{{isset($page_title) && ($page_title=='User Dashboard') ? 'active' : ''}} ">
                <a href="{{url('official/user/dashboard')}}"><i class="clip-home-3"></i>
                    <span class="title"> Dashboard </span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="{{isset($page_title) && ($page_title=='Profile') ? 'active' : ''}} ">
                <a href="{{url('official/user/profile')}}"><i class="clip-user-2"></i>
                    <span class="title"> My Profile </span>
                    <span class="selected"></span>
                </a>
            </li>
        @endif
    </ul>
    <!-- end: MAIN NAVIGATION MENU -->
</div>