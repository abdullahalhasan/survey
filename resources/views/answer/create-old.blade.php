@extends('layout.master')
@section('content')
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
        <div class="col-md-12">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="form-group">
                <a href="{{ url('admin/survey/campaign') }}" class=" btn btn-success btn-squared ">
                    <i class="fa fa-bar-chart-o"></i> All campaign
                </a>
            </div>
            <form role="form" action="{{url('admin/question/answer/store')}}" method="post" class="form-horizontal" id="myform">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                <div class="panel panel-default btn-squared">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i>
                        Questions
                        <div class="panel-tools">
                            <strong>
                                Page number: {{ $page_number }}
                            </strong>
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
                            <div class="col-md-12">
                                <?php
                                $questions = \App\Question::getAllSurveyQuestionByPageNumber($page_number,$campaign_id)
                                ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        @if(!empty($questions) && count($questions)>0)
                                            @foreach($questions as $key => $question)
                                                <tr>
                                                    <td width="85%">
                                                        <strong>
                                                            Q - {{ $key+1 }} : {{ isset($question->question_title) ? $question->question_title: '' }}
                                                        </strong>
                                                        <span class="help-block">{{ isset($question->question_help_text) ? 'Help text: '.$question->question_help_text:'' }}</span>
                                                        <input type="hidden" name="page_number" value="{{$question->question_page_no}}">
                                                        <input type="hidden" name="mas" value="{{$question->question_page_no}}">
                                                        <input type="hidden" name="question_id" value="{{ $question->id }}">
                                                        @if($question->question_input_type_id == 1)
                                                            <?php
                                                            $option_groups = \DB::table('question_option_group')
                                                                ->where('option_question_id',$question->id)
                                                                ->where('option_input_type_id',$question->question_input_type_id)
                                                                ->get();
                                                            ?>
                                                            @if($question->question_answer_require == 1)
                                                                @if($question->masking_enable == 1)
                                                                    <?php
                                                                    foreach ($option_groups as $op) {
                                                                        $ex_option[$op->id] = $op->question_option_name;
                                                                    }
                                                                    $mask_ref_id = \DB::table('question_meta')
                                                                        ->select('meta_question_field_value')
                                                                        ->where('question_id',$question->id)
                                                                        ->where('meta_question_field_name','masked_question_id')
                                                                        ->where('campaign_id',$campaign_id)
                                                                        ->first();
                                                                    $limit = \DB::table('count_mask_rmask')
                                                                        ->where('user_id',\Auth::user()->id)
                                                                        ->where('type','mask')
                                                                        ->latest()
                                                                        ->first();
                                                                    $answerOptions = \DB::table('question_answer')
                                                                        ->where('answer_question_id',$mask_ref_id->meta_question_field_value)
                                                                        ->where('answer_user_id',\Auth::user()->id)
                                                                        ->limit($limit->total_option)
                                                                        ->orderBy('id','DESC')
                                                                        ->get();
                                                                    foreach ($answerOptions as $answerOption) {
                                                                        $ansOptionArray[$answerOption->answer_option_group_id] = $answerOption->answer_option_group_name;
                                                                    }
                                                                    $new_array = array_intersect($ex_option,$ansOptionArray);
                                                                    //print_r($new_array);
                                                                    ?>
                                                                    @if(!empty($new_array) && count($new_array) > 0)
                                                                        @foreach($new_array as $key=>$val)
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" value="{{$key}}" name="answer_option_group_value[]_{{$question->id}}" class="grey myClassName">
                                                                                    {{$val}}
                                                                                    <input type="hidden" value="{{$question->id}}" name="answer_question_id[]">
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        @if(!empty($option_groups) && count($option_groups) > 0)
                                                                            @foreach($option_groups as $key=>$group)
                                                                                <div class="radio">
                                                                                    <label>
                                                                                        <input type="radio" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey myClassName">
                                                                                        {{$group->question_option_name}}
                                                                                        <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <p><strong>Option not found.</strong></p>
                                                                        @endif
                                                                    @endif
                                                                @elseif($question->remasking_enable == 1)
                                                                    <?php
                                                                    foreach ($option_groups as $op) {
                                                                        $ex_option[$op->id] = $op->question_option_name;
                                                                    }
                                                                    $mask_ref_id = \DB::table('question_meta')
                                                                        ->select('meta_question_field_value')
                                                                        ->where('question_id',$question->id)
                                                                        ->where('meta_question_field_name','re_masked_question_id')
                                                                        ->where('campaign_id',$campaign_id)
                                                                        ->first();
                                                                    $limit = \DB::table('count_mask_rmask')
                                                                        ->where('user_id',\Auth::user()->id)
                                                                        ->where('type','rmask')
                                                                        ->latest()
                                                                        ->first();
                                                                    $answerOptions = \DB::table('question_answer')
                                                                        ->where('answer_question_id',$mask_ref_id->meta_question_field_value)
                                                                        ->where('answer_user_id',\Auth::user()->id)
                                                                        ->limit($limit->total_option)
                                                                        ->orderBy('id','DESC')
                                                                        ->get();
                                                                    foreach ($answerOptions as $answerOption) {
                                                                        $ansOptionArray[$answerOption->answer_option_group_id] = $answerOption->answer_option_group_name;
                                                                    }
                                                                    $new_array =  array_diff($ex_option, $ansOptionArray);
                                                                    ?>
                                                                    @if(!empty($new_array) && count($new_array) > 0)
                                                                        @foreach($new_array as $key=>$val)
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" value="{{$key}}" name="answer_option_group_value[]_{{$question->id}}" class="grey myClassName">
                                                                                    {{$val}}
                                                                                    <input type="hidden" value="{{$question->id}}" name="answer_question_id[]">
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        @if(!empty($option_groups) && count($option_groups) > 0)
                                                                            @foreach($option_groups as $key=>$group)
                                                                                <div class="radio">
                                                                                    <label>
                                                                                        <input type="radio" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey myClassName">
                                                                                        {{$group->question_option_name}}
                                                                                        <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <p><strong>Option not found.</strong></p>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @foreach($option_groups as $key=>$group)
                                                                        <div class="radio">
                                                                            <label>
                                                                                <input type="radio" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey myClassName">
                                                                                {{$group->question_option_name}}
                                                                                <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            @else
                                                                @if($question->masking_enable == 1)
                                                                    <?php
                                                                    foreach ($option_groups as $op) {
                                                                        $ex_option[$op->id] = $op->question_option_name;
                                                                    }
                                                                    $mask_ref_id = \DB::table('question_meta')
                                                                        ->select('meta_question_field_value')
                                                                        ->where('question_id',$question->id)
                                                                        ->where('meta_question_field_name','masked_question_id')
                                                                        ->where('campaign_id',$campaign_id)
                                                                        ->first();
                                                                    $limit = \DB::table('count_mask_rmask')
                                                                        ->where('user_id',\Auth::user()->id)
                                                                        ->where('type','mask')
                                                                        ->latest()
                                                                        ->first();
                                                                    $answerOptions = \DB::table('question_answer')
                                                                        ->where('answer_question_id',$mask_ref_id->meta_question_field_value)
                                                                        ->where('answer_user_id',\Auth::user()->id)
                                                                        ->limit($limit->total_option)
                                                                        ->orderBy('id','DESC')
                                                                        ->get();
                                                                    foreach ($answerOptions as $answerOption) {
                                                                        $ansOptionArray[$answerOption->answer_option_group_id] = $answerOption->answer_option_group_name;
                                                                    }
                                                                    $new_array = array_intersect($ex_option,$ansOptionArray);
                                                                    //print_r($new_array);
                                                                    ?>
                                                                    @if(!empty($new_array) && count($new_array) > 0)
                                                                        @foreach($new_array as $key=>$val)
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" value="{{$key}}" name="answer_option_group_value[]_{{$question->id}}" class="grey">
                                                                                    {{$val}}
                                                                                    <input type="hidden" value="{{$question->id}}" name="answer_question_id[]">
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        @if(!empty($option_groups) && count($option_groups) > 0)
                                                                            @foreach($option_groups as $key=>$group)
                                                                                <div class="radio">
                                                                                    <label>
                                                                                        <input type="radio" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                        {{$group->question_option_name}}
                                                                                        <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <p><strong>Option not found.</strong></p>
                                                                        @endif
                                                                    @endif
                                                                @elseif($question->remasking_enable == 1)
                                                                    <?php
                                                                    foreach ($option_groups as $op) {
                                                                        $ex_option[$op->id] = $op->question_option_name;
                                                                    }
                                                                    $mask_ref_id = \DB::table('question_meta')
                                                                        ->select('meta_question_field_value')
                                                                        ->where('question_id',$question->id)
                                                                        ->where('meta_question_field_name','re_masked_question_id')
                                                                        ->where('campaign_id',$campaign_id)
                                                                        ->first();
                                                                    $limit = \DB::table('count_mask_rmask')
                                                                        ->where('user_id',\Auth::user()->id)
                                                                        ->where('type','rmask')
                                                                        ->latest()
                                                                        ->first();
                                                                    $answerOptions = \DB::table('question_answer')
                                                                        ->where('answer_question_id',$mask_ref_id->meta_question_field_value)
                                                                        ->where('answer_user_id',\Auth::user()->id)
                                                                        ->limit($limit->total_option)
                                                                        ->orderBy('id','DESC')
                                                                        ->get();
                                                                    foreach ($answerOptions as $answerOption) {
                                                                        $ansOptionArray[$answerOption->answer_option_group_id] = $answerOption->answer_option_group_name;
                                                                    }
                                                                    $new_array =  array_diff($ex_option, $ansOptionArray);
                                                                    ?>
                                                                    @if(!empty($new_array) && count($new_array) > 0)
                                                                        @foreach($new_array as $key=>$val)
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" value="{{$key}}" name="answer_option_group_value[]_{{$question->id}}" class="grey">
                                                                                    {{$val}}
                                                                                    <input type="hidden" value="{{$question->id}}" name="answer_question_id[]">
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        @if(!empty($option_groups) && count($option_groups) > 0)
                                                                            @foreach($option_groups as $key=>$group)
                                                                                <div class="radio">
                                                                                    <label>
                                                                                        <input type="radio" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                        {{$group->question_option_name}}
                                                                                        <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <p><strong>Option not found.</strong></p>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @foreach($option_groups as $key=>$group)
                                                                        <div class="radio">
                                                                            <label>
                                                                                <input type="radio" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                {{$group->question_option_name}}
                                                                                <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        @elseif($question->question_input_type_id == 2)
                                                            <?php
                                                            $option_groups = \DB::table('question_option_group')
                                                                ->where('option_question_id',$question->id)
                                                                ->where('option_input_type_id',$question->question_input_type_id)
                                                                ->get();
                                                            ?>
                                                            @if($question->question_answer_require == 1)
                                                                @if($question->masking_enable == 1)
                                                                    <?php
                                                                    foreach ($option_groups as $op) {
                                                                        $ex_option[$op->id] = $op->question_option_name;
                                                                    }
                                                                    $mask_ref_id = \DB::table('question_meta')
                                                                        ->select('meta_question_field_value')
                                                                        ->where('question_id',$question->id)
                                                                        ->where('meta_question_field_name','masked_question_id')
                                                                        ->where('campaign_id',$campaign_id)
                                                                        ->first();
                                                                    $limit = \DB::table('count_mask_rmask')
                                                                        ->where('user_id',\Auth::user()->id)
                                                                        ->where('type','mask')
                                                                        ->latest()
                                                                        ->first();
                                                                    $answerOptions = \DB::table('question_answer')
                                                                        ->where('answer_question_id',$mask_ref_id->meta_question_field_value)
                                                                        ->where('answer_user_id',\Auth::user()->id)
                                                                        ->limit($limit->total_option)
                                                                        ->orderBy('id','DESC')
                                                                        ->get();
                                                                    foreach ($answerOptions as $answerOption) {
                                                                        $ansOptionArray[$answerOption->answer_option_group_id] = $answerOption->answer_option_group_name;
                                                                    }
                                                                    $new_array = array_intersect($ex_option,$ansOptionArray);
                                                                    //print_r($new_array);
                                                                    ?>
                                                                    @if(!empty($new_array) && count($new_array) > 0)
                                                                        @foreach($new_array as $key=>$val)
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="hidden" value="{{$question->id}}" name="answer_question_id[]">
                                                                                    <input type="checkbox" value="{{$key}}" name="answer_option_group_value[]_{{$question->id}}" class="grey myClassName">
                                                                                    {{$val}}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        @if(!empty($option_groups) && count($option_groups) > 0)
                                                                            @foreach($option_groups as $key=>$group)
                                                                                <div class="checkbox">
                                                                                    <label>
                                                                                        <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                        <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey myClassName">
                                                                                        {{$group->question_option_name}}
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <p><strong>Option not found.</strong></p>
                                                                        @endif
                                                                    @endif
                                                                @elseif ($question->remasking_enable == 1)
                                                                    <?php
                                                                    foreach ($option_groups as $op) {
                                                                        $ex_option[$op->id] = $op->question_option_name;
                                                                    }
                                                                    $mask_ref_id = \DB::table('question_meta')
                                                                        ->select('meta_question_field_value')
                                                                        ->where('question_id',$question->id)
                                                                        ->where('meta_question_field_name','re_masked_question_id')
                                                                        ->where('campaign_id',$campaign_id)
                                                                        ->first();
                                                                    $limit = \DB::table('count_mask_rmask')
                                                                        ->where('user_id',\Auth::user()->id)
                                                                        ->where('type','rmask')
                                                                        ->latest()
                                                                        ->first();
                                                                    $answerOptions = \DB::table('question_answer')
                                                                        ->where('answer_question_id',$mask_ref_id->meta_question_field_value)
                                                                        ->where('answer_user_id',\Auth::user()->id)
                                                                        ->limit($limit->total_option)
                                                                        ->orderBy('id','DESC')
                                                                        ->get();
                                                                    foreach ($answerOptions as $answerOption) {
                                                                        $ansOptionArray[$answerOption->answer_option_group_id] = $answerOption->answer_option_group_name;
                                                                    }
                                                                    $new_array =  array_diff($ex_option, $ansOptionArray);
                                                                    ?>
                                                                    @if(!empty($new_array) && count($new_array) > 0)
                                                                        @foreach($new_array as $key=>$val)
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="hidden" value="{{$question->id}}" name="answer_question_id[]">
                                                                                    <input type="checkbox" value="{{$key}}" name="answer_option_group_value[]_{{$question->id}}" class="grey myClassName">
                                                                                    {{$val}}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        @if(!empty($option_groups) && count($option_groups) > 0)
                                                                            @foreach($option_groups as $key=>$group)
                                                                                <div class="checkbox">
                                                                                    <label>
                                                                                        <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                        <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey myClassName">
                                                                                        {{$group->question_option_name}}
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <p><strong>Option not found.</strong></p>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                    <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey myClassName">
                                                                                    {{$group->question_option_name}}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <p><strong>Option not found.</strong></p>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if($question->masking_enable == 1)
                                                                    <?php
                                                                    foreach ($option_groups as $op) {
                                                                        $ex_option[$op->id] = $op->question_option_name;
                                                                    }
                                                                    $mask_ref_id = \DB::table('question_meta')
                                                                        ->select('meta_question_field_value')
                                                                        ->where('question_id',$question->id)
                                                                        ->where('meta_question_field_name','masked_question_id')
                                                                        ->where('campaign_id',$campaign_id)
                                                                        ->first();
                                                                    $limit = \DB::table('count_mask_rmask')
                                                                        ->where('user_id',\Auth::user()->id)
                                                                        ->where('type','mask')
                                                                        ->latest()
                                                                        ->first();
                                                                    $answerOptions = \DB::table('question_answer')
                                                                        ->where('answer_question_id',$mask_ref_id->meta_question_field_value)
                                                                        ->where('answer_user_id',\Auth::user()->id)
                                                                        ->limit($limit->total_option)
                                                                        ->orderBy('id','DESC')
                                                                        ->get();
                                                                    foreach ($answerOptions as $answerOption) {
                                                                        $ansOptionArray[$answerOption->answer_option_group_id] = $answerOption->answer_option_group_name;
                                                                    }
                                                                    $new_array = array_intersect($ex_option,$ansOptionArray);
                                                                    //print_r($new_array);
                                                                    ?>
                                                                    @if(!empty($new_array) && count($new_array) > 0)
                                                                        @foreach($new_array as $key=>$val)
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="hidden" value="{{$question->id}}" name="answer_question_id[]">
                                                                                    <input type="checkbox" value="{{$key}}" name="answer_option_group_value[]_{{$question->id}}" class="grey">
                                                                                    {{$val}}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        @if(!empty($option_groups) && count($option_groups) > 0)
                                                                            @foreach($option_groups as $key=>$group)
                                                                                <div class="checkbox">
                                                                                    <label>
                                                                                        <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                        <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                        {{$group->question_option_name}}
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <p><strong>Option not found.</strong></p>
                                                                        @endif
                                                                    @endif
                                                                @elseif ($question->remasking_enable == 1)
                                                                    <?php
                                                                    foreach ($option_groups as $op) {
                                                                        $ex_option[$op->id] = $op->question_option_name;
                                                                    }
                                                                    $mask_ref_id = \DB::table('question_meta')
                                                                        ->select('meta_question_field_value')
                                                                        ->where('question_id',$question->id)
                                                                        ->where('meta_question_field_name','re_masked_question_id')
                                                                        ->where('campaign_id',$campaign_id)
                                                                        ->first();
                                                                    $limit = \DB::table('count_mask_rmask')
                                                                        ->where('user_id',\Auth::user()->id)
                                                                        ->where('type','rmask')
                                                                        ->latest()
                                                                        ->first();
                                                                    $answerOptions = \DB::table('question_answer')
                                                                        ->where('answer_question_id',$mask_ref_id->meta_question_field_value)
                                                                        ->where('answer_user_id',\Auth::user()->id)
                                                                        ->limit($limit->total_option)
                                                                        ->orderBy('id','DESC')
                                                                        ->get();
                                                                    foreach ($answerOptions as $answerOption) {
                                                                        $ansOptionArray[$answerOption->answer_option_group_id] = $answerOption->answer_option_group_name;
                                                                    }
                                                                    $new_array =  array_diff($ex_option, $ansOptionArray);
                                                                    ?>
                                                                    @if(!empty($new_array) && count($new_array) > 0)
                                                                        @foreach($new_array as $key=>$val)
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="hidden" value="{{$question->id}}" name="answer_question_id[]">
                                                                                    <input type="checkbox" value="{{$key}}" name="answer_option_group_value[]_{{$question->id}}" class="grey">
                                                                                    {{$val}}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        @if(!empty($option_groups) && count($option_groups) > 0)
                                                                            @foreach($option_groups as $key=>$group)
                                                                                <div class="checkbox">
                                                                                    <label>
                                                                                        <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                        <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                        {{$group->question_option_name}}
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <p><strong>Option not found.</strong></p>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                    <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                    {{$group->question_option_name}}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <p><strong>Option not found.</strong></p>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <strong>Question not found.</strong>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" style="padding-right: 10px;">
                    <input class="btn btn-success  btn-squared pull-right" name="submit" value="Next" type="submit">
                </div>
            </form>
        </div>
    </div>
    <!--modal for company input type-->
    <div id="ajax-more-question" class="modal fade" tabindex="-1" style="display: none;" data-width="60%"></div>
    <!--modal for company input type-->
@endsection
@section('JScript')
    <script>
        $(function () {
            $("#myform").validate({
                errorClass: "my-error-class",
                submitHandler: function(form) {
                    // do other things for a valid form
                    form.submit();
                }
            });
            jQuery.validator.addClassRules('myClassName', {
                required: true /*,
                 other rules */
            });
        })
    </script>
@endsection
