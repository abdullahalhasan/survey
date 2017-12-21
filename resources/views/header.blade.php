<header>
    <div role="navigation" class="navbar navbar-default navbar-fixed-top" style="height:130px;">
        <!-- start: TOP NAVIGATION CONTAINER -->
        <div class="container">
            <div class="navbar-header">
                <!-- start: RESPONSIVE MENU TOGGLER -->
                <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- end: RESPONSIVE MENU TOGGLER -->
                <!-- start: LOGO -->
                <a class="navbar-brand" href="{{ url('/') }}">
                   <img src="{{ asset('public/front_end/images/mindscape_logo.png') }}">
                </a>
                <!-- end: LOGO -->
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                @if(\Auth::check())
                    <?php
                        $user = \DB::table('users')
                            ->where('id',\Auth::user()->id)
                            ->first();
                    ?>
                    @if(count($user) > 0)
                        @if($user->mobile_verified == '0')
                            <a href="{{ url('pin/confirm/'.$user->user_mobile) }}" class="btn btn-squared btn-success">Please verify your mobile number</a>
                        @endif
                    @endif
                @endif
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="{{isset($page_title) && ($page_title=='Home') ? 'active' : ''}}">
                        <!--class="active"-->
                        <a href="{{ url('/') }}">
                            Home
                        </a>
                    </li>
                    <li class="{{isset($page_title) && ($page_title=='All Campaign') ? 'active' : ''}}">
                        <a href="{{ url('all/campaign') }}">
                            Campaign List
                        </a>
                    </li>
                    {{--<li class="{{isset($page_title) && ($page_title=='Call Logs') ? 'active' : ''}}">
                        <a href="{{ url('call/logs') }}">
                            Call Logs
                        </a>
                    </li>
                    <li class="{{isset($page_title) && ($page_title=='SMS Logs') ? 'active' : ''}}">
                        <a href="{{ url('sms/logs') }}">
                            SMS Logs
                        </a>
                    </li>--}}
                    @if(\Auth::check())
                        <?php
                            $first_name=explode(' ', \Auth::user()->name);
                        ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown" data-hover="dropdown">
                                Account setting <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ url('profile/edit/'.\Crypt::encrypt(\Auth::user()->id)) }}">
                                        Profile update
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('change/password/'.\Crypt::encrypt(\Auth::user()->id)) }}">
                                        Change password
                                    </a>
                                </li>
                                <li>
                                    <a href="{{url('logout/'.\Auth::user()->name_slug)}}">Sign Out</a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="{{isset($page_title) && ($page_title=='Sign Up') ? 'active' : ''}}">
                            <a  href="{{ url('sign-up') }}">
                                Sign Up
                            </a>
                        </li>
                        <li class="{{isset($page_title) && ($page_title=='Sign In') ? 'active' : ''}}">
                            <a  href="{{ url('sign-in') }}">
                                Sign In
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- end: TOP NAVIGATION CONTAINER -->
    </div>
</header>