@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="panel panel-default btn-squared">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i>
                    All companies
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <a href="{{ url('admin/company/create') }}" class=" btn btn-success btn-squared "><i class="fa fa-plus"></i> Create </a>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <input type="text" id="name" class="form-control" name="name" placeholder="search by name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <input type="text" id="mobile" class="form-control" name="mobile" placeholder="search by mobile number">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table_date">
                        <table class="table table-hover table-bordered table-striped nopadding">
                        <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Address</th>
                            <th>Mobile</th>
                            <th>Web site</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($companies) && count($companies) > 0)
                            @foreach($companies as $company)
                                <tr>
                                    <td>{{ $company->name }}</td>
                                    <td>{{ $company->address }}</td>
                                    <td>{{ $company->mobile }}</td>
                                    <td><a href="{{ $company->web_url }}" target="_blank"> {{ $company->web_url }}</a> </td>
                                    <td>
                                        <a class="btn btn-info btn-xs btn-squared company-show" href="javascript:void(0)"
                                           data-company-id="{{$company->id}}">
                                            <i class="fa fa-search"></i> Show
                                        </a>
                                        <a class="btn btn-primary btn-xs btn-squared" href="{{ url('admin/company/edit/'.$company->id) }}">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a class="btn btn-danger btn-xs btn-squared delete"  href="javascript:void(0)"
                                           data-company-id="{{$company->id}}">
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
                        {{isset($company_pagination) ? $company_pagination:""}}
                    </div>
                </div>
            </div>
            <!-- end: FORM VALIDATION 2 PANEL -->
        </div>
    </div>
    <!--modal for company input type-->
    <div id="ajax-company-show" class="modal fade" tabindex="-1" style="display: none;" data-width="50%"></div>
    <!--modal for company input type-->
@endsection

@section('JScript')
    <script>
        $(function () {
            var site_url = $('.site_url').val();
            $('.delete').on('click', function () {
                var id = $(this).data('company-id');
                swal({
                    title: 'Are you sure?',
                    text: "You want to delete this company",
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
                                url: site_url + '/admin/company/delete/'+id,
                                success: function (data) {
                                    if(data.status == 0){
                                        swal('Oops...','Something is wrong!','error')
                                        return
                                    }
                                    swal(
                                        'Done!',
                                        'This company delete successfully',
                                        'success'
                                    )
                                    window.location.href = site_url+'/admin/company'
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
                            'You do not delete this company)',
                            'error'
                        )
                        window.location.href = site_url+'/admin/company'
                    }
                });
            });
            $('#name').on('keyup',function () {
                var name = $(this).val();
                $.ajax({
                    Type: "GET",
                    url: site_url + '/admin/company/search/name',
                    data: { name:name },
                    success: function (data) {
                        $('.table_date').html(data)
                    },
                });

            });
            $('#mobile').on('keyup',function () {
                var mobile = $(this).val();
                $.ajax({
                    Type: "GET",
                    url: site_url + '/admin/company/search/mobile',
                    data: { mobile:mobile },
                    success: function (data) {
                        $('.table_date').html(data)
                    },
                });

            });
        })
    </script>
@endsection