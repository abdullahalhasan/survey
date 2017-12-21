@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="panel panel-default btn-squared">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i>
                    Edit Survey Campaign
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
                            <a href="{{ url('admin/survey/campaign') }}" class=" btn btn-success btn-squared "><i class="fa fa-reorder"></i>
                                All Survey Campaign
                            </a>
                        </div>
                    </div>
                    <form action="{{ url('admin/survey/campaign/update/'.$surveyCampaign->id) }}" method="post" role="form" id="survey-campaign-form">
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
                                        <strong>Campaign Category<span class="symbol required"></span></strong>
                                    </label>
                                    <select id="form-field-select-3" class="form-control search-select" name="campaign_category_id">
                                        <option value="">&nbsp;Please select campaign category</option>
                                        @if(!empty($categories) && count($categories) > 0)
                                            @foreach($categories as $category)
                                                <option {{($surveyCampaign->campaign_category_id == $category->id) ? "selected" :''}}
                                                        value="{{$category->id}}">{{$category->name}}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="">Data not found.</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Campaign title <span class="symbol required"></span></strong>
                                    </label>
                                    <input type="text" name="campaign_title" placeholder="Please insert campaign title"
                                           class="form-control" value="{{$surveyCampaign->campaign_title}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Campaign owner <span class="symbol required"></span></strong>
                                    </label>
                                    <input type="text" name="campaign_owner" placeholder="Please insert campaign owner"
                                           class="form-control" value="{{$surveyCampaign->campaign_owner}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Campaign active date <span class="symbol required"></span></strong>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years"
                                               class="form-control date-picker" name="active_date" value="{{$surveyCampaign->active_date}}">
                                        <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">
                                                <strong>Campaign expiry date <span class="symbol required"></span></strong>
                                            </label>
                                            <div class="input-group">
                                                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years"
                                                       class="form-control date-picker" name="expire_date" value="{{$surveyCampaign->expire_date}}">
                                                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Campaign incentive amount <span class="symbol required"></span></strong>
                                    </label>
                                    <input type="text" name="campaign_incentive_amount" placeholder="Please insert incentive amount"
                                           class="form-control" value="{{$surveyCampaign->campaign_incentive_amount}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Campaign incentive point <span class="symbol required"></span></strong>
                                    </label>
                                    <input type="text" name="campaign_incentive_point" placeholder="Please insert incentive point"
                                           class="form-control" value="{{$surveyCampaign->campaign_incentive_point}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Campaign instructions <span class="symbol required"></span></strong>
                                    </label>
                                    <input type="text" name="campaign_instructions" placeholder="Please insert campaign instructions"
                                           class="form-control" value="{{$surveyCampaign->campaign_instructions}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Campaign ending text <span class="symbol required"></span></strong>
                                    </label>
                                    <input type="text" name="campaign_ending_text" placeholder="Please insert campaign ending text"
                                           class="form-control" value="{{$surveyCampaign->campaign_ending_text}}">
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
            $('#survey-campaign-form').validate({
                rules: {
                    campaign_category_id: {
                        required: true
                    },
                    campaign_title: {
                        required: true
                    },
                    campaign_owner: {
                        required: true
                    },
                    active_date: {
                        required: true,
                    },
                    expire_date: {
                        required: true,
                    },
                    campaign_incentive_amount: {
                        required : true,
                        number: true
                    },
                    campaign_incentive_point: {
                        required: true,
                        number: true
                    },
                    campaign_instructions : {
                        required : true
                    },
                    campaign_ending_text : {
                        required : true,
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