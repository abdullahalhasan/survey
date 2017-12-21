<!DOCTYPE html>
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html class="no-js">
<!--<![endif]-->
<head>
    <title>{{isset($page_title) ? $page_title : ''}} | Mindscape</title>
    <link rel="shortcut icon" href="favicon.ico" />
    <!-- start: META -->
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="Survey System Version 1.0" name="description" />
    <meta content="Survey" name="live" />
    <!-- end: META -->
    <!-- start: MAIN CSS -->
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/font-awesome/css/font-awesome.min.css') }}">

    <link type="text/css" rel="stylesheet" href="{{ asset('public/front_end/css/animate.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('public/front_end/fonts/clip-font.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('public/front_end/css/main.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('public/front_end/css/main-responsive.min.css') }}" />
    <link type="text/css" rel="stylesheet" id="skin_color" href="{{ asset('public/front_end/css/theme_blue.min.css') }}" />
    <!-- end: MAIN CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    <!-- RS5.0 Main Stylesheet -->
    <link href="{{ asset('public/front_end/plugins/slider-revolution/css/settings.css') }}" rel="stylesheet" />
    <!-- RS5.0 Layers and Navigation Styles -->
    <link href="{{ asset('public/front_end/plugins/slider-revolution/css/layers.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/front_end/plugins/slider-revolution/css/navigation.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/front_end/flexslider/flexslider.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/front_end/jquery-colorbox/example2/colorbox.css') }}" rel="stylesheet" />
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    <style type="text/css">
        .my-error-class {
            color:#FF0000;  /* red */
        }
    </style>
</head>
<body style="padding-top: 5px;">
<div class="main-container">
    @yield('content')
</div>
<!-- start: MAIN JAVASCRIPTS -->
<!--[if lt IE 9]>
<script src="{{ asset('public/assets/plugins/respond.min.js') }}"></script>
<script src="{{ asset('public/assets/html5shiv/dist/html5shiv.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jQuery-lib/1.10.2/jquery.min.js') }}"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script src="{{ asset('public/assets/plugins/jQuery-lib/2.0.3/jquery.min.js') }}"></script>
<!--<![endif]-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>

<script src="{{ asset('public/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/blockUI/jquery.blockUI.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/assets/jquery.transit/jquery.transit.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/assets/jquery_appear/jquery.appear.js') }}"></script>
<script src="{{ asset('public/assets/plugins/jquery-cookie/jquery.cookie.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/front_end/js/min/main.min.js') }}"></script>
<!-- end: MAIN JAVASCRIPTS -->
<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<!-- RS5.0 Core JS Files -->
<script src="{{ asset('public/front_end/plugins/slider-revolution/js/jquery.themepunch.tools.min838f.js') }}"></script>
<script src="{{ asset('public/front_end/plugins/slider-revolution/js/source/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{ asset('public/front_end/plugins/slider-revolution/js/jquery.themepunch.revolution.min838f.js') }}"></script>
<script src="{{ asset('public/assets/flexslider/jquery.flexslider-min.js') }}"></script>
<script src="{{ asset('public/assets/jquery.stellar/jquery.stellar.min.js') }}"></script>
<script src="{{ asset('public/assets/jquery-colorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('public/front_end/js/min/index.min.js') }}"></script>


<script type="text/javascript" src="{{ asset('public/front_end/plugins/slider-revolution/js/extensions/revolution.extension.actions.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/front_end/plugins/slider-revolution/js/extensions/revolution.extension.carousel.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('public/front_end/plugins/slider-revolution/js/extensions/revolution.extension.kenburn.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('public/front_end/plugins/slider-revolution/js/extensions/revolution.extension.layeranimation.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('public/front_end/plugins/slider-revolution/js/extensions/revolution.extension.migration.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('public/front_end/plugins/slider-revolution/js/extensions/revolution.extension.navigation.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('public/front_end/plugins/slider-revolution/js/extensions/revolution.extension.parallax.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('public/front_end/plugins/slider-revolution/js/extensions/revolution.extension.slideanims.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('public/front_end/plugins/slider-revolution/js/extensions/revolution.extension.video.min.js')}}"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script>
    jQuery(document).ready(function() {
        Main.init();
        Index.init();
        $.stellar();
    });
</script>
@yield('JScript')
<input type="hidden" class="site_url" value="{{url('/')}}" >
</body>
</html>