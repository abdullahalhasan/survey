@extends('masterForApp')
@section('content')
    <div class="main-container">
        <section class="page-top">
            <div class="container">
                <div class="col-md-4 col-sm-4">
                    <h1>Title:</h1>
                </div>
                <div class="col-md-8 col-sm-8">
                    <ul class="pull-right breadcrumb">
                        <li>
                            Page number
                        </li>
                        <li>
                            1
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <section class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- start: FORM VALIDATION 2 PANEL -->
                                    <form role="form" action="{{url('question/')}}" method="post" class="form-horizontal" id="Myform">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <div class="panel panel-default btn-squared">
                                            <div class="panel-heading">
                                                <i class="fa fa-external-link-square"></i>
                                                Questions
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-control">
                                                    <input type="text" name="page_number">
                                                </div>
                                                <div class="form-control">
                                                    <input class="btn btn-success  btn-squared pull-right" name="submit" value="Next" type="submit">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('JScript')
    <script>
        $(function () {
            $('#Myform').validate({
                rules: {
                    page_number: {
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
                        element.attr("placeholder",error.text());
                    }
                }
            });
        })
    </script>
@endsection