@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="panel panel-default btn-squared">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i>
                    All Survey Campaign
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
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <a href="{{ url('admin/survey/campaign/create') }}" class=" btn btn-success btn-squared "><i class="fa fa-plus"></i> Create Campaign</a>
                            </div>
                        </div>
                        <div class="col-md-7"></div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <input type="text" id="title" class="form-control" name="campaign_title" placeholder="search by title">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table_date">
                        <table class="table table-hover table-bordered table-striped nopadding">
                            <thead>
                            <tr>
                                <th>Campaign Category</th>
                                <th>Campaign Title</th>
                                <th>Active date</th>
                                <th>Expire date</th>
                                <th>Incentive amount(BDT)</th>
                                <th>Incentive point</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($surveyCampaigns) && count($surveyCampaigns) > 0)
                                @foreach($surveyCampaigns as $surveyCampaign)
                                    <tr>
                                        <td>
                                            <a class="survey-campaign-show" href="javascript:void(0)"
                                               data-survey-campaign-id="{{$surveyCampaign->id}}">
                                                {{ $surveyCampaign->name }}
                                            </a>
                                        </td>
                                        <td>{{ $surveyCampaign->campaign_title }}</td>
                                        <td>{{ $surveyCampaign->active_date }}</td>
                                        <td>{{ $surveyCampaign->expire_date}}</td>
                                        <td>{{ $surveyCampaign->campaign_incentive_amount}}</td>
                                        <td>{{ $surveyCampaign->campaign_incentive_point}}</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs btn-squared" href="{{ url('admin/survey/campaign/edit/'.$surveyCampaign->id) }}">
                                                Edit
                                            </a>
                                            <a class="btn btn-danger btn-xs btn-squared delete"  href="javascript:void(0)"
                                               data-survey-campaign-id="{{$surveyCampaign->id}}">
                                                Delete
                                            </a>
                                            @if($surveyCampaign->status == 1)
                                                <a class="btn btn-info btn-xs btn-squared" disabled="" href="javascript:void(0);">
                                                   Create Question
                                                </a>
                                            @else
                                                <a class="btn btn-info btn-xs btn-squared"  href="{{ url('admin/question/create/'.$surveyCampaign->id) }}">
                                                    Create Q
                                                </a>

                                                <a class="btn btn-success btn-xs btn-squared"  href="{{ url('admin/question/answer/create/'.$surveyCampaign->id.'/1'.'/'.\Auth::user()->id) }}">
                                                    Answer Q
                                                </a>

                                            @endif
                                            {{--@if($surveyCampaign->status == 1)
                                                <a class="btn btn-success btn-xs btn-squared unpublished" title="Published"  href="javascript:void(0)"
                                                   data-survey-campaign-id="{{$surveyCampaign->id}}">
                                                    Published
                                                </a>
                                            @else
                                                <a class="btn btn-danger btn-xs btn-squared published" title="Unpublished"  href="javascript:void(0)"
                                                   data-survey-campaign-id="{{$surveyCampaign->id}}">
                                                    Unpublished
                                                </a>
                                            @endif--}}
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
                        {{isset($surveyCampaigns) ? $surveyCampaigns:""}}
                    </div>
                </div>
            </div>
            <!-- end: FORM VALIDATION 2 PANEL -->
        </div>
    </div>
    <!--modal for company input type-->
    <div id="ajax-survey-campaign-show" class="modal fade" tabindex="-1" style="display: none;" data-width="60%"></div>
    <!--modal for company input type-->
@endsection

@section('JScript')
    <script>
        $(function () {
            var site_url = $('.site_url').val();
            $('.delete').on('click', function () {
                var id = $(this).data('survey-campaign-id');
                swal({
                    title: 'Are you sure?',
                    text: "You want to delete this survey campaign",
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
                                url: site_url + '/admin/survey/campaign/delete/'+id,
                                success: function (data) {
                                    if(data.status == 0){
                                        swal('Oops...','Something is wrong!','error')
                                        return
                                    }
                                    swal(
                                        'Done!',
                                        'This survey campaign delete successfully',
                                        'success'
                                    )
                                    window.location.href = site_url+'/admin/survey/campaign'
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
                            'You do not delete this survey campaign)',
                            'error'
                        )
                        window.location.href = site_url+'/admin/survey/campaign'
                    }
                });
            });
            $('.published').on('click', function () {
                var campaign_id = $(this).data('survey-campaign-id');
                swal({
                    title: 'Are you sure?',
                    text: "You want to publish this survey campaign",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, publish it!',
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
                                url: site_url + '/admin/survey/campaign/publish/'+campaign_id,
                                success: function (data) {
                                    if(data.status == 0){
                                        swal('Please','Create question first!','info')
                                        return
                                    }
                                    swal(
                                        'Done!',
                                        'This survey campaign has been published',
                                        'success'
                                    )

                                    window.location.href = site_url+'/admin/survey/campaign'
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
                            'You do not publish this survey campaign)',
                            'error'
                        )
                    }

                });
            });

            $('.unpublished').on('click', function () {
                var campaign_id = $(this).data('survey-campaign-id');
                swal({
                    title: 'Are you sure?',
                    text: "You want to unpublish this survey campaign",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Unpublish it!',
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
                                url: site_url + '/admin/survey/campaign/unpublish/'+campaign_id,
                                success: function (data) {
                                    if(data.status == 0){
                                        swal('Oops...','Something is wrong!','error')
                                        return
                                    }
                                    swal(
                                        'Done!',
                                        'This survey campaign has been unpublished',
                                        'success'
                                    )
                                    window.location.href = site_url+'/admin/survey/campaign'
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
                            'You do not unpublish this survey campaign)',
                            'error'
                        )
                    }

                });
            });

            $('#title').on('keyup',function () {
                var campaign_title = $(this).val();
                $.ajax({
                    Type: "GET",
                    url: site_url + '/admin/survey/campaign/search/title',
                    data: { campaign_title:campaign_title },
                    success: function (data) {
                        $('.table_date').html(data)
                    },
                });

            });
        })
    </script>
@endsection