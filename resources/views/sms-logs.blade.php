@extends('master')
@section('content')
    <section class="page-top">
        <div class="container">
            <div class="col-md-4 col-sm-4">
                <h1>Campaign list</h1>
            </div>
            <div class="col-md-8 col-sm-8">
                <ul class="pull-right breadcrumb">
                    <li>
                        <a href="{{ url('/') }}">
                            Home
                        </a>
                    </li>
                    <li class="active">
                        SMS Logs
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
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>User Mobile</th>
                                <th>Contact Name</th>
                                <th>SMS Type</th>
                                <th>SMS Text</th>
                                <th>SMS Date</th>
                                <th>Last Updated</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($sms_logs) && count($sms_logs) > 0)
                                @foreach($sms_logs as $sms_log)
                                    <tr>
                                        <td>{{ $sms_log->user_mobile }}</td>
                                        <td>{{ $sms_log->sms_address }}</td>
                                        <td>{{ $sms_log->sms_type }}</td>
                                        <td>{{ $sms_log->sms_text}}</td>
                                        <td>{{ $sms_log->sms_date}}</td>
                                        <td>{{ $sms_log->created_at}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="11">No Data available</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        {{isset($smsLogsPagination) ? $smsLogsPagination:""}}
                    </div>
                </div>
            </div>
            <br><br><br><br><br><br><br><br><br><br><br><br>
        </div>
    </section>
@endsection