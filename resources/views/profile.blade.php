@extends('master')
@section('content')
    <section class="page-top">
        <div class="container">
            <div class="col-md-4 col-sm-4">
                <h1>Profile Update</h1>
            </div>
            <div class="col-md-8 col-sm-8">
                <ul class="pull-right breadcrumb">
                    <li>
                        <a href="{{ url('/') }}">
                            Home
                        </a>
                    </li>
                    <li class="active">
                        Profile Update
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    &nbsp;
                </div>
                <div class="col-md-8">
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
                        <div class="alert alert-success btn-squared" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    @if (Session::has('errormessage'))
                        <div class="alert alert-danger btn-squared" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('errormessage') }}
                        </div>
                    @endif
                    <form type="post" id="sign-up" action="{{ url('profile/update/'.$user->id) }}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>
                                        Your name <span class="symbol required"></span>
                                    </label>
                                    <input type="text" value="{{$user->name}}"  name="name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>
                                        Your mobile <span class="symbol required"></span>
                                    </label>
                                    <input type="text" value="{{$user->user_mobile}}"  name="user_mobile" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>
                                        Your email address <span class="symbol required"></span>
                                    </label>
                                    <input type="email" value="{{$user->email}}"  name="email" class="form-control" >
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                &nbsp;
                            </div>
                            <div class="col-md-8">
                                <input type="submit" class="btn btn-main-color btn-squared pull-right" value="Update">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-2">
                    &nbsp;
                </div>
            </div>
        </div>
    </section>
    <br><br>
@endsection
@section('JScript')
    <script>
        $(function () {
            $('#sign-up').validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email:true,
                    },
                    user_mobile: {
                        required:true,
                        digits:true,
                        maxlength: 11
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