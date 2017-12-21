@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="panel panel-default btn-squared">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i>
                    Create Campaign Category
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
                    <form action="{{ url('admin/campaign/category/update/'.$category->id) }}" method="post" role="form" id="campaign-category-form">
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
                                        <strong>Category Name <span class="symbol required"></span></strong>
                                    </label>
                                    <input type="text" name="name" value="{{$category->name}}" placeholder="Please insert company name" class="form-control">
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
        <div class="col-md-6">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="panel panel-default btn-squared">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i>
                    All Campaign Category
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <a href="{{ url('admin/campaign/category') }}" class=" btn btn-success btn-squared "><i class="fa fa-plus"></i> Create </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <input type="text" class="form-control name" name="name" placeholder="search by name">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table_date">
                        <table class="table table-hover table-bordered table-striped nopadding">
                            <thead>
                            <tr>
                                <th>Category Name</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($categories) && count($categories) > 0)
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            <a class="btn btn-info btn-xs btn-squared campaign-category-show" href="javascript:void(0)"
                                               data-campaign-category-id="{{$category->id}}">
                                                <i class="fa fa-search"></i> Show
                                            </a>
                                            <a class="btn btn-primary btn-xs btn-squared" href="{{ url('admin/campaign/category/edit/'.$category->id) }}">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a class="btn btn-danger btn-xs btn-squared delete"  href="javascript:void(0)"
                                               data-campaign-category-id="{{$category->id}}">
                                                <i class="fa fa-trash-o"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="11">No Data available</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        {{isset($category_pagination) ? $category_pagination:""}}
                    </div>
                </div>
            </div>
            <!-- end: FORM VALIDATION 2 PANEL -->
        </div>
    </div>
    <!--modal for company input type-->
    <div id="ajax-campaign-category-show" class="modal fade" tabindex="-1" style="display: none;" data-width="50%"></div>
    <!--modal for company input type-->
@endsection

@section('JScript')
    <script>
        $(function () {
            $('#campaign-category-form').validate({
                rules: {
                    name: {
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
            var site_url = $('.site_url').val();
            $('.delete').on('click', function () {
                var id = $(this).data('campaign-category-id');
                swal({
                    title: 'Are you sure?',
                    text: "You want to delete this category",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: true,
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            setTimeout(function() {
                                resolve();
                            }, 2000);
                        });
                    }
                }).then(function () {
                    setTimeout(function() {
                        setTimeout(function() {
                            $.ajax({
                                Type: "GET",
                                url: site_url + '/admin/campaing/category/delete/'+id,
                                success: function (data) {
                                    if(data.status == 0){
                                        swal('Oops...','Something is wrong!','error')
                                        return
                                    }
                                    swal(
                                        'Done!',
                                        'This category delete successfully',
                                        'success'
                                    )
                                    window.location.href = site_url+'/admin/campaign/category'
                                },
                                dataType:"json"
                            });

                        }, 50);
                    }, 50);
                }, function (dismiss) {
                    // dismiss can be 'cancel', 'overlay',
                    // 'close', and 'timer'
                    if (dismiss === 'cancel') {
                        swal(
                            'Cancelled',
                            'You do not delete this category)',
                            'error'
                        )
                        window.location.href = site_url+'/admin/campaign/category'
                    }
                });
            });
            $('.name').on('keyup',function () {
                var name = $(this).val();
                $.ajax({
                    Type: "GET",
                    url: site_url + '/admin/campaign/category/search/name',
                    data: { name:name },
                    success: function (data) {
                        $('.table_date').html(data)
                    },
                });
            });
        })

    </script>
@endsection