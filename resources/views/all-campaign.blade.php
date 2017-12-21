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
                        All Campaign
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
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
                    <div class="errorHandler alert alert-danger no-display">
                        <i class="fa fa-times-sign"></i> You have some form errors. Please check below.
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
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
                                            <?php
                                            $user = \DB::table('users')
                                                ->where('id',\Auth::user()->id)
                                                ->first();
                                            ?>
                                            @if($user->mobile_verified == '0')
                                                <a class="btn btn-success btn-xs btn-squared"  href="{{ url('pin/confirm/'.$user->user_mobile) }}">
                                                    Answer
                                                </a>
                                            @else
                                                <a class="btn btn-success btn-xs btn-squared"  href="{{ url('question/answer/'.$surveyCampaign->id.'/1'.'/'.\Auth::user()->id) }}">
                                                    Answer
                                                </a>
                                            @endif
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
        </div>
    </section>
@endsection