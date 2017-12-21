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
                        Call Logs
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
                                <th>Contact Number</th>
                                <th>Call Type</th>
                                <th>Call Date</th>
                                <th>Call Duration</th>
                                <th>Last Updated</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($call_logs) && count($call_logs) > 0)
                                @foreach($call_logs as $call_log)
                                    <tr>
                                        <td>{{ $call_log->user_mobile }}</td>
                                        <td>{{ $call_log->contact_name }}</td>
                                        <td>{{ $call_log->contact_number }}</td>
                                        <td>{{ $call_log->call_type}}</td>
                                        <td>{{ $call_log->call_time}}</td>
                                        <td>{{ $call_log->call_duration}}</td>
                                        <td>{{ $call_log->created_at}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="11">No Data available</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        {{isset($callLogsPagination) ? $callLogsPagination:""}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection