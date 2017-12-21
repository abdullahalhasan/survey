<!DOCTYPE html>
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <title>{{isset($page_title) ? $page_title : ''}} | Mindscape</title>
    <link rel="icon" href="{{asset('public/frontend/assets/images/favicon.ico')}}">
    <!-- start: META -->
    <meta charset="utf-8" />
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="MyBazaar" name="description" />
    <meta content="MyBazaar" name="MyBazaar" />
    <!-- end: META -->
    <!-- start: MAIN CSS -->
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Raleway:400,100,200,300,500,600,700,800,900/" />
    <link type="text/css" rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{asset('public/assets/plugins/font-awesome/css/font-awesome.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{ asset('public/assets/fonts/clip-font.min.css') }}" />
    <link rel="stylesheet" href="{{asset('public/assets/plugins/iCheck/skins/all.css')}}">
    <link type="text/css" rel="stylesheet" href="{{ asset('public/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('public/assets/plugins//sweetalert/sweetalert.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('public/assets/css/main.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('public/assets/css/main-responsive.min.css') }}" />
    <link type="text/css" rel="stylesheet" media="print" href="{{ asset('public/assets/css/print.min.css') }}" />
    <link type="text/css" rel="stylesheet" id="skin_color" href="{{ asset('public/assets/css/light.min.css') }}" />
    <!-- end: MAIN CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->


</head>

<body class="error-full-page rain-page">

<img class="rain-image" id="background" src="" />

<!-- start: PAGE -->
<div class="container">
    <div class="row">
        <!-- start: 404 -->
        <div class="col-sm-12 page-error">
            <div class="error-number teal">
                500
            </div>
            <div class="error-details col-sm-6 col-sm-offset-3">
                <h3>Oops! You are stuck at 500</h3>
                <p>
                   It may be temporarily unavailable, moved or no longer exist.
                    <br>
                    <a href="{{ url('/') }}" class="btn btn-teal btn-return">
                        Return home
                    </a>
                </p>
            </div>
        </div>
        <!-- end: 404 -->
    </div>
</div>
<!-- end: PAGE -->
<!-- start: MAIN JAVASCRIPTS -->
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="{{ asset('public/assets/plugins/respond.min.js')}}"></script>
<script src="{{ asset('public/assets/plugins/excanvas.min.js')}}"></script>
<script type="text/javascript" src=" {{ asset('public/plugins/jQuery-lib/1.10.2/jquery.min.js')}}assets/"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script src="{{asset('public/assets/plugins/jQuery-lib/2.0.3/jquery.min.js')}}"></script>
<!--<![endif]-->
<script type="text/javascript" src="{{ asset('public/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{asset('public/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/blockUI/jquery.blockUI.js')}}"></script>
<script src="{{asset('public/assets/plugins/iCheck/jquery.icheck.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-cookie/jquery.cookie.js')}}"></script>
<script type="text/javascript" src="{{ asset('public/assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/assets/js/main.min.js') }}"></script>
<!-- end: MAIN JAVASCRIPTS -->
<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script src="{{ asset('public/assets/plugins/rainyday/rainyday/rainyday.min.js') }}"></script>
<script src="{{ asset('public/assets/js/utility-error404.js') }}"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->


<script>
    jQuery(document).ready(function() {
        Main.init();
        Error404.init();
    });
</script>

</body>

</html>