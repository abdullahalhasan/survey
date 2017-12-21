@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="panel panel-default btn-squared">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i>
                    Create Company
                    <div class="panel-tools">
                        <a class="btn btn-xs btn-link panel-collapse collapses" href="#">
                        </a>
                        <a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a class="btn btn-xs btn-link panel-refresh" href="#">
                            <i class="fa fa-refresh"></i>
                        </a>
                        <a class="btn btn-xs btn-link panel-expand" href="#">
                            <i class="fa fa-resize-full"></i>
                        </a>
                        <a class="btn btn-xs btn-link panel-close" href="#">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
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
                    <div class="col-md-2" style="margin-top:5px;">
                        <div class="form-group">
                            <a href="{{ url('admin/company') }}" class=" btn btn-success btn-squared "><i class="fa fa-reorder"></i> All Companies </a>
                        </div>
                    </div>
                    <form action="{{ url('admin/company/store') }}" method="post" role="form" id="company-form">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="errorHandler alert alert-danger no-display btn-squared">
                                    <i class="fa fa-times-sign"></i> You have some form errors. Please check below.
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Company Name <span class="symbol required"></span></strong>
                                    </label>
                                    <input type="text" name="name" placeholder="Please insert company name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Address <span class="symbol required"></span></strong>
                                    </label>
                                    <input type="text" name="address" placeholder="Please insert company address" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Mobile Number <span class="symbol required"></span></strong>
                                    </label>
                                    <input type="text" name="mobile" placeholder="Please insert mobile number" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Web site address <span class="symbol required"></span></strong>
                                    </label>
                                    <input type="text" name="web_url" placeholder="Please insert company web site address" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">
                                                <strong>About Company <span class="symbol required"></span></strong>
                                            </label>
                                            {{--<div class="summernote"></div>--}}
                                            <textarea class="form-control" name="description" cols="10" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <span class="symbol required"></span>Required Fields
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input class="btn btn-success  btn-squared pull-right" name="submit" value="Save" type="submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end: FORM VALIDATION 2 PANEL -->
        </div>
    </div>
@endsection
@section('JScript')
    <script>
        $(function () {
            $('#company-form').validate({
                rules: {
                    name: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    mobile: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    web_url: {
                        url: true
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
                        element.attr("placeholder",error.text());
                    }
                }
            });
        })

    </script>
@endsection