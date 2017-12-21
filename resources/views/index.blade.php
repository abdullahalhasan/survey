@extends('master')
@section('content')
    <!-- start: REVOLUTION SLIDERS -->
    <section class="fullwidthbanner-container">
        <div id="fullwidthabnner">
    <ul>
        <!-- start: FIRST SLIDE -->
        <li data-transition="fade">
            <!-- MAIN IMAGE -->
            <img src="{{ asset('public/front_end/images/sliders/slidebg1.png') }}" style="background-color:rgb(246, 246, 246)" alt="slidebg1" data-bgfit="cover" data-bgposition="left bottom" data-bgrepeat="no-repeat">
            <!-- LAYER NR. 1 -->
            <div class="tp-caption lft slide_title slide_item_left" data-x="right" data-hoffset="-150" data-y="bottom" data-voffset="0" data-whitespace="normal" data-start="500">
                <img src="{{ asset('public/front_end/images/free-woman.png') }}" alt="Image 1">
            </div>
            <!-- LAYER NR. 2 -->
            <div class="tp-caption lft slide_title slide_item_left" data-x="left" data-hoffset="0" data-y="top" data-voffset="105" data-speed="400" data-start="1500" data-easing="easeOutExpo" data-width="full">
                Free your creative spirit
            </div>
            <!-- LAYER NR. 3 -->
            <div class="caption tp-caption lft slide_subtitle slide_item_left" data-x="left" data-hoffset="0" data-y="top" data-voffset="180" data-speed="400" data-start="2000" data-easing="easeOutExpo" data-width="255px">
                Super Clean Admin Theme
            </div>
            <!-- LAYER NR. 4 -->
            <div class="caption tp-caption lft slide_desc slide_item_left" data-x="left" data-hoffset="0" data-y="top" data-voffset="220" data-speed="400" data-start="2500" data-easing="easeOutExpo" data-width="full">
                Lorem ipsum dolor sit amet, dolore eiusmod
                <br> quis tempor incididunt. Sed unde omnis iste.
            </div>
            <!-- LAYER NR. 5 -->
            <a class="tp-caption lft btn btn-green slide_btn slide_item_left" target="_blank" href="http://themeforest.net/item/clipone-bootstrap-3-responsive-admin-template/5881143?ref=Cliptheme" data-x="left" data-hoffset="0" data-y="top" data-voffset="320" data-speed="400"
               data-start="3000" data-easing="easeOutExpo">
                Purchase Now!
            </a>
        </li>
        <!-- end: FIRST SLIDE -->
        <!-- start: SECOND SLIDE -->
        <li data-transition="fade">
            <!-- MAIN IMAGE -->
            <img src="{{ asset('public/front_end/images/sliders/slidebg2.png') }}" style="background-color:rgb(246, 246, 246)" alt="slidebg1" data-bgfit="cover" data-bgposition="left bottom" data-bgrepeat="no-repeat">
            <!-- LAYER NR. 1 -->
            <div class="tp-caption lft slide_title slide_item_left" data-x="left" data-hoffset="0" data-y="top" data-voffset="105" data-speed="400" data-start="1500" data-easing="easeOutExpo" data-width="full">
                100% Fully Responsive
            </div>
            <!-- LAYER NR. 2 -->
            <div class="tp-caption lfl slide_subtitle slide_item_left" data-x="left" data-hoffset="0" data-y="top" data-voffset="180" data-speed="400" data-start="2000" data-easing="easeOutExpo" data-width="325px">
                The best possible Web experience
            </div>
            <!-- LAYER NR. 3 -->
            <div class="tp-caption lfr slide_desc slide_item_left" data-x="left" data-hoffset="0" data-y="top" data-voffset="220" data-speed="400" data-start="2500" data-easing="easeOutExpo" data-width="full">
                Lorem ipsum dolor sit amet, dolore eiusmod
                <br> quis tempor incididunt. Sed unde omnis iste.
            </div>
            <!-- LAYER NR. 4 -->
            <a class="tp-caption lfb btn btn-bricky slide_btn slide_item_left" target="_blank" href="http://themeforest.net/item/clipone-bootstrap-3-responsive-admin-template/5881143?ref=Cliptheme" data-x="left" data-hoffset="0" data-y="top" data-voffset="320" data-speed="400"
               data-start="3000" data-easing="easeOutExpo">
                Purchase Now!
            </a>
            <!-- LAYER NR. 5 -->
            <div class="tp-caption lft" data-x="680" data-y="85" data-speed="500" data-start="1000" data-easing="easeOutBack">
                <img src="{{ asset('public/front_end/images/sliders/responsive1.png')}}" alt="Image 1">
            </div>
            <!-- LAYER NR. 6 -->
            <div class="tp-caption lfb" data-x="770" data-y="115" data-speed="500" data-start="1300" data-easing="easeOutBack">
                <img src="{{ asset('public/front_end/images/sliders/responsive2.png')}}" alt="Image 1">
            </div>
            <!-- LAYER NR. 7 -->
            <div class="tp-caption lft" data-x="820" data-y="140" data-speed="500" data-start="1600" data-easing="easeOutBack">
                <img src="{{ asset('public/front_end/images/sliders/responsive3.png')}}" alt="Image 1">
            </div>
            <!-- LAYER NR. 8 -->
            <div class="tp-caption lfb" data-x="880" data-y="160" data-speed="500" data-start="1900" data-easing="easeOutBack">
                <img src="{{ asset('public/front_end/images/sliders/responsive4.png') }}" alt="Image 1">
            </div>
        </li>
        <!-- end: SECOND SLIDE -->
        <!-- start: THIRD SLIDE -->
        <li data-transition="fade">
            <!-- MAIN IMAGE -->
            <img src="{{ asset('public/front_end/images/sliders/slidebg3.png')}}" style="background-color:rgb(246, 246, 246)" alt="slidebg1" data-bgfit="cover" data-bgposition="left bottom" data-bgrepeat="no-repeat">
            <!-- LAYER NR. 1 -->
            <div class="tp-caption lft slide_title slide_item_left" data-x="0" data-y="105" data-speed="400" data-start="1500" data-easing="easeOutExpo" data-width="full">
                Super Flexible Layout
            </div>
            <!-- LAYER NR. 2 -->
            <div class="tp-caption sft slide_subtitle slide_item_left" data-x="0" data-y="180" data-speed="400" data-start="2000" data-easing="easeOutExpo" data-width="460px">
                Clip-One allows you to create unique website
            </div>
            <!-- LAYER NR. 3 -->
            <div class="tp-caption sfr slide_desc slide_item_left" data-x="0" data-y="220" data-speed="400" data-start="2500" data-easing="easeOutExpo" data-width="full">
                Lorem ipsum dolor sit amet, dolore eiusmod
                <br> quis tempor incididunt. Sed unde omnis iste.
            </div>
            <!-- LAYER NR. 4 -->
            <a class="tp-caption sfb btn btn-purple slide_btn slide_item_left" target="_blank" href="http://themeforest.net/item/clipone-bootstrap-3-responsive-admin-template/5881143?ref=Cliptheme" data-x="0" data-y="320" data-speed="400" data-start="3000" data-easing="easeOutExpo">
                Purchase Now!
            </a>
            <!-- LAYER NR. 5 -->
            <div class="tp-caption sfr" data-x="800" data-y="115" data-speed="500" data-start="1000" data-easing="easeOutBack">
                <img src="{{ asset('public/front_end/images/sliders/device1.png')}}" alt="Image 1">
            </div>
            <!-- LAYER NR. 6 -->
            <div class="tp-caption sfr" data-x="710" data-y="225" data-speed="500" data-start="1300" data-easing="easeOutBack">
                <img src="{{ asset('public/front_end/images/sliders/device2.png')}}" alt="Image 1">
            </div>
            <!-- LAYER NR. 7 -->
            <div class="tp-caption sfr" data-x="860" data-y="300" data-speed="500" data-start="1600" data-easing="easeOutBack">
                <img src="{{ asset('public/front_end/images/sliders/device3.png')}}" alt="Image 1">
            </div>
        </li>
        <!-- end: THIRD SLIDE -->
    </ul>
    </div>
    </section>
    <!-- end: REVOLUTION SLIDERS -->

    <section class="wrapper padding50">
    <!-- start: ABOUT US CONTAINER -->
    <div class="container">
    <div class="row">
        <div class="col-sm-6">
            <h2 style="text-align: right;">About Us</h2>
            <hr class="fade-left">
            <p style="text-align: right;">
                Lorem ipsum dolor sit amet, consectetuer <strong>adipiscing elit</strong>. Aenean commodo ligula eget dolor. Aenean massa.
            </p>
            <p style="text-align: right;">
                Nulla consequat massa quis enim.
                <br> Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.
                <br> In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.
                <br> Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi.
                <br> Aenean <strong style="text-align: right;">vulputate</strong> eleifend tellus.
            </p>
            <hr class="fade-left">
            <a href="#" class="btn btn-default pull-right"><i class="fa fa-info"></i> Learn more...</a>
        </div>
        <div class="col-sm-6">
            <ul class="icon-list animate-group">
                <li>
                    <div class="timeline animate" data-animation-options='{"animation":"scaleToBottom", "duration":"300"}'></div>
                    <i class="clip-stack-empty circle-icon circle-teal animate" data-animation-options='{"animation":"flipInY", "duration":"600"}'></i>
                    <div class="icon-list-content">
                        <h4>HTML5 / CSS3 / JS</h4>
                        <p>
                            Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in.
                        </p>
                    </div>
                </li>
                <li>
                    <div class="timeline animate" data-animation-options='{"animation":"scaleToBottom", "duration":"300", "delay": "300"}'></div>
                    <i class="clip-paperplane circle-icon circle-green animate" data-animation-options='{"animation":"flipInY", "duration":"600"}'></i>
                    <div class="icon-list-content">
                        <h4>Awesome Sliders</h4>
                        <p>
                            Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in.
                        </p>
                    </div>
                </li>
                <li>
                    <div class="timeline animate" data-animation-options='{"animation":"scaleToBottom", "duration":"300", "delay": "300"}'></div>
                    <i class="clip-droplet circle-icon circle-bricky animate" data-animation-options='{"animation":"flipInY", "duration":"600"}'></i>
                    <div class="icon-list-content">
                        <h4>Clean Design</h4>
                        <p>
                            Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in.
                        </p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    </div>
    <!-- end: ABOUT US CONTAINER -->
    </section>
@endsection