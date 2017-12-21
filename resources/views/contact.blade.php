@extends('master')
@section('content')
<section class="page-top">
    <div class="container">
        <div class="col-md-4 col-sm-4">
            <h1>Contact Us</h1>
        </div>
        <div class="col-md-8 col-sm-8">
            <ul class="pull-right breadcrumb">
                <li>
                    <a href="{{ url('/') }}">
                        Home
                    </a>
                </li>
                <li class="active">
                    Contact Us
                </li>
            </ul>
        </div>
    </div>
</section>
<section class="wrapper padding50">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h4>Get in touch</h4>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae velit congue iaculis vitae at risus.
                </p>
                <hr>
                <h4>The Office</h4>
                <ul class="list-unstyled">
                    <li>
                        <i class="icon icon-map-marker"></i><strong>Address:</strong> 1234 Street Name, City Name, United States
                    </li>
                    <li>
                        <i class="icon icon-phone"></i><strong>Phone:</strong> (123) 456-7890
                    </li>
                    <li>
                        <i class="icon icon-envelope"></i><strong>Email:</strong>
                        <a href="mailto:mail@example.com">
                            mail@example.com
                        </a>
                    </li>
                </ul>
                <hr class="right">
                <h4>Business Hours</h4>
                <ul class="list-unstyled">
                    <li>
                        <i class="icon icon-time"></i> Monday - Friday 9am to 5pm
                    </li>
                    <li>
                        <i class="icon icon-time"></i> Saturday - 9am to 2pm
                    </li>
                    <li>
                        <i class="icon icon-time"></i> Sunday - Closed
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                @if($errors->count() > 0 )
                    <div class="alert alert-danger btn-squared">
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
                    <div class="alert alert-success btn-squared" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('message') }}
                    </div>
                @endif
                @if(Session::has('errormessage'))
                    <div class="alert alert-danger btn-squared" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('errormessage') }}
                    </div>
                @endif
                <h2>Contact Us</h2>
                <form type="post" id="contact" action="{{ url('send/mail') }}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label>
                                    Your name <span class="symbol required"></span>
                                </label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>
                                    Your email address <span class="symbol required"></span>
                                </label>
                                <input type="email"  name="email" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>
                                    Subject
                                </label>
                                <input type="text"  name="subject" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>
                                    Message <span class="symbol required"></span>
                                </label>
                                <textarea  name="message" class="form-control" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" data-loading-text="Loading..." class="btn btn-main-color btn-squared pull-right" value="Send Message">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('JScript')
    <script>
        $(function () {
            $('#contact').validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email:true,
                    },
                    subject: {
                        required: true,
                        maxlength: 100,
                    },
                    message: {
                        required: true,
                    }

                },
                highlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                },
                errorClass: 'my-error-class',
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                        element.attr("placeholder",error.text());
                    }
                }
            });
        })
    </script>
@endsection