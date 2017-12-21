<!DOCTYPE html>
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js" ng-app="surveyApp">
<!--<![endif]-->
<!-- start: HEAD -->
<head>
    <title>{{isset($page_title) ? $page_title : ''}} | Mindscape</title>
    <!-- start: META -->
    <meta charset="utf-8" />
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- end: META -->
    <!-- start: MAIN CSS -->
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/fonts/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/rating.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/main-responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/iCheck/skins/all.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/theme_light.css') }}" type="text/css" id="skin_color">
    <link rel="stylesheet" href="{{ asset('public/assets/css/print.css') }}" type="text/css" media="print"/>
    <!--[if IE 7]>
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/font-awesome/css/font-awesome-ie7.min.css') }}">
    <![endif]-->
    <!-- end: MAIN CSS -->
    <link href="{{ asset('public/assets/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/assets/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/summernote/build/summernote.css') }}">
    <!--SweetalertCSS-->
    <link rel="stylesheet" href="{{asset('public/assets/plugins/sweetalert/sweetalert2.min.css')}}" type="text/css" />
    <!-- Form elements-->
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/datepicker/css/datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/jQuery-Tags-Input/jquery.tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/summernote/build/summernote.css') }}">
    {{--<link rel="stylesheet" href="{{ asset('public/assets/angular/lib/angular-growl.min.css') }}">--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/jquery.jgrowl.min.css') }}" />
    <link rel="shortcut icon" href="favicon.ico" />
    <style type="text/css">
        fieldset.scheduler-border {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
        }
        legend.scheduler-border {
            font-size: 1.1em !important;
            text-align: left !important;
            width:auto;
            padding:0 10px;
            border-bottom:none;
        }
        label.error{
            color: red !important;
            font-weight: normal !important;
        }
        .my-error-class {
            color:#FF0000;  /* red */
        }
        .jGrowl .custom {
            background-color: #4F4F4F;
        }
        .bootbox {
            left: auto !important;
            width: auto !important;
            margin-left: auto !important;
            background-color: transparent !important;
            border: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            position: relative !important;
            top: 50% !important;
            overflow: visible !important;
            transition: opacity 0.15s linear 0s !important;
        }
        body.dragging, body.dragging * {
            cursor: move !important;
        }

        .dragged {
            position: absolute;
            opacity: 0.5;
            z-index: 2000;
        }

        ul.example li.placeholder {
            position: relative;
            /** More li styles **/
        }
        ul.example li.placeholder:before {
            position: absolute;
            /** Define arrowhead **/
        }



    </style>
</head>
<!-- end: HEAD -->
<!-- start: BODY -->
<body>
<!-- start: HEADER -->
@include('layout.header')
<!-- end: HEADER -->
<!-- start: MAIN CONTAINER -->
<div class="main-container">
    <div class="navbar-content">
        <!-- start: SIDEBAR -->
        @include('layout.sidebar')
        <!-- end: SIDEBAR -->
    </div>
    <!-- start: PAGE -->
    <div class="main-content">
        <!-- end: SPANEL CONFIGURATION MODAL FORM -->
        <div class="container">
            <!-- start: PAGE HEADER -->
            @include('layout.breadcrumb')
            <!-- end: PAGE HEADER -->
            <!-- start: PAGE CONTENT -->
            @yield('content')
            <!-- end: PAGE CONTENT-->
        </div>
    </div>
    <!-- end: PAGE -->
</div>
<!-- end: MAIN CONTAINER -->
<!-- start: FOOTER -->
<div class="footer clearfix">
    <div class="footer-inner">
        &copy; Copyright 2017 by Mindscape.All Rights Reserved.Powered by <a href="http://www.live-technologies.net/" target="_blank">Live Technologies.</a>
    </div>
    <div class="footer-items">
        <span class="go-top"><i class="clip-chevron-up"></i></span>
    </div>
</div>
<!-- end: FOOTER -->
<!-- start: MAIN JAVASCRIPTS -->
<!--[if lt IE 9]>
<script src="{{ asset('public/assets/plugins/respond.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/excanvas.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jQuery-lib/1.10.2/jquery.min.js') }}"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script src="{{ asset('public/assets/plugins/jQuery-lib/2.0.3/jquery.min.js') }}"></script>
<!--<![endif]-->
<script src="{{ asset('public/assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/blockUI/jquery.blockUI.js') }}"></script>
<script src="{{ asset('public/assets/plugins/iCheck/jquery.icheck.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('public/assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('public/assets/plugins/less/less-1.5.0.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/jquery-cookie/jquery.cookie.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js') }}"></script>
<script src="{{ asset('public/assets/js/main.js') }}"></script>
<!-- end: MAIN JAVASCRIPTS -->

<script src="{{ asset('public/assets/plugins/bootstrap-modal/js/bootstrap-modal.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}"></script>
<script src="{{ asset('public/assets/js/ui-modals.js') }}"></script>

<script src="{{ asset('public/assets/plugins/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/summernote/build/summernote.min.js') }}"></script>
<!--sweetlertJs-->
<script src="{{asset('public/assets/plugins/sweetalert/sweetalert2.min.js')}}"></script>

<script src="{{ asset('public/assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/autosize/jquery.autosize.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/jquery.maskedinput/src/jquery.maskedinput.js') }}"></script>
<script src="{{ asset('public/assets/plugins/jquery-maskmoney/jquery.maskMoney.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-colorpicker/js/commits.js') }}"></script>
<script src="{{ asset('public/assets/plugins/jQuery-Tags-Input/jquery.tagsinput.js') }}"></script>
<script src="{{ asset('public/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/summernote/build/summernote.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/plugins/ckeditor/adapters/jquery.js') }}"></script>
<script src="{{ asset('public/assets/js/form-elements.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.jgrowl.min.js') }}"></script>
<script src="{{ asset('public/assets/js/bootbox.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery-sortable-min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore.js"></script>
{{--
<script src="{{ asset('public/assets/angular/lib/angular.min.js') }}"></script>
<script src="{{ asset('public/assets/angular/lib/angular-animate.min.js') }}"></script>
<script src="{{ asset('public/assets/angular/lib/angular-resource.min.js') }}"></script>
<script src="{{ asset('public/assets/angular/lib/angular-sanitize.min.js') }}"></script>
<script src="{{ asset('public/assets/angular/lib/angular-growl.min.js') }}"></script>
<script src="{{ asset('public/assets/angular/app.js') }}"></script>
--}}

<script>
    jQuery(document).ready(function() {
        Main.init();
        UIModals.init();
        FormElements.init();
    });
    var APP_URL = '{!! url('/') !!}';
</script>
@yield('JScript')
<input type="hidden" class="site_url" value="{{url('/')}}" >
</body>
<!-- end: BODY -->
</html>