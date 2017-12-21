@extends('master')
@section('content')
    <section class="page-top">
        <div class="container">
            <div class="col-md-4 col-sm-4">
                <h1>Title: {{$cam_name->campaign_title}}</h1>
            </div>
            <div class="col-md-8 col-sm-8">
                <ul class="pull-right breadcrumb">
                    <li>
                        Page number
                    </li>
                    <li>
                        {{$page_number}}
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
                                <form role="form" action="{{url('question/answer/post/'.$user_id)}}" method="post" class="form-horizontal" id="myform">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                                    <div class="panel panel-default btn-squared">
                                        <div class="panel-heading">
                                            <i class="fa fa-external-link-square"></i>
                                            Question(s)
                                            <div class="panel-tools pull-right">
                                                <strong>
                                                    Page number: {{ $page_number }}
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php
                                                    $questions = \App\Question::getAllSurveyQuestionByPageNumber($page_number,$campaign_id)
                                                    ?>
                                                    <div class="table-responsive">
                                                        <table class="table" style="margin-bottom: 1px;">
                                                            @if(!empty($questions) && count($questions)>0)
                                                                @foreach($questions as $key => $question)
                                                                    @if($question->question_input_type_id == 13)
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                <input type="hidden" name="page_number" value="{{$question->question_page_no}}">
                                                                                <input type="hidden" name="question_id" value="{{ $question->id }}">
                                                                                <input type="hidden" name="answer_option_group_value[]" value="landing page">
                                                                                <input type="hidden" name="question_input_type_id" value="{{$question->question_input_type_id}}">
                                                                                <blockquote><strong>{{ isset($question->question_title) ? $question->question_title: '' }}</strong></blockquote>
                                                                            </td>
                                                                        </tr>
                                                                    @elseif($question->question_input_type_id == 14)
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                <input type="hidden" name="page_number" value="{{$question->question_page_no}}">
                                                                                <input type="hidden" name="question_id" value="{{ $question->id }}">
                                                                                <input type="hidden" name="answer_option_group_value[]" value="landing page">
                                                                                <input type="hidden" name="question_input_type_id" value="{{$question->question_input_type_id}}">
                                                                                <blockquote><strong>{{ isset($question->question_title) ? $question->question_title: '' }}</strong></blockquote>
                                                                            </td>
                                                                        </tr>
                                                                    @else
                                                                        <tr>
                                                                            <td>
                                                                                <strong>
                                                                                    Q - {{ $key+1 }} :
                                                                                    {{ isset($question->question_title) ? $question->question_title: '' }}
                                                                                    {{ isset($question->branching_enable) ? 'Branching': '' }}
                                                                                </strong>
                                                                                <span class="help-block">{{ isset($question->question_help_text) ? 'Help text: '.$question->question_help_text:'' }}</span>
                                                                                <input type="hidden" name="page_number" value="{{$question->question_page_no}}">
                                                                                <input type="hidden" name="question_id" value="{{ $question->id }}">
                                                                                @if($question->question_input_type_id == 1)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    <table>
                                                                                        @if($question->question_answer_require == 1 )
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
                                                                                                        <div class="radio">
                                                                                                            <label>
                                                                                                                <input type="radio" value="{{$key}}" name="answer_option_group_value[]_{{$question->id}}" class="grey myClassName">
                                                                                                                {{$val}}
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
                                                                                                        <tr>
                                                                                                            <td>
                                                                                                                <div class="radio">
                                                                                                                    <label>
                                                                                                                        <input type="radio" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey myClassName">
                                                                                                                        {{$group->question_option_name}}
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    <tr>
                                                                                                        <td>Option not found</td>
                                                                                                    </tr>
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
                                                                                                        <div class="radio">
                                                                                                            <label>
                                                                                                                <input type="radio" value="{{$key}}" name="answer_option_group_value[]_{{$question->id}}" class="grey">
                                                                                                                {{$val}}
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
                                                                                                        <tr>
                                                                                                            <td>
                                                                                                                <div class="radio">
                                                                                                                    <label>
                                                                                                                        <input type="radio" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                                                        {{$group->question_option_name}}
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    <tr>
                                                                                                        <td>Option not found</td>
                                                                                                    </tr>
                                                                                                @endif
                                                                                            @endif
                                                                                        @endif
                                                                                    </table>
                                                                                @elseif($question->question_input_type_id == 2)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    <table>
                                                                                        @if($question->question_answer_require == 1 )
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
                                                                                                                    <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey myClassName">
                                                                                                                    {{$group->question_option_name}}
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
                                                                                                        <div class="checkbox">
                                                                                                            <label>
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
                                                                                                        <tr>
                                                                                                            <td>
                                                                                                                <div class="checkbox">
                                                                                                                    <label>
                                                                                                                        <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey myClassName ">
                                                                                                                        {{$group->question_option_name}}
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    <tr>
                                                                                                        <td>Option not found</td>
                                                                                                    </tr>
                                                                                                @endif
                                                                                            @endif
                                                                                        @else
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div class="checkbox">
                                                                                                                <label>
                                                                                                                    <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                                                    {{$group->question_option_name}}
                                                                                                                </label>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endif
                                                                                    </table>
                                                                                @elseif($question->question_input_type_id == 3)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    @if($question->question_answer_require == 1 )
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
                                                                                            <select  class="form-control myClassName" name="answer_option_group_value[]_{{$question->id}}">
                                                                                                @if(!empty($new_array) && count($new_array) > 0)
                                                                                                    @foreach($new_array as $key=>$val)
                                                                                                        <option value="{{$key}}">{{$val}}</option>
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                        @foreach($option_groups as $key=>$group)
                                                                                                            <option value="{{ $group->id }}">{{$group->question_option_name}}</option>
                                                                                                        @endforeach
                                                                                                    @else
                                                                                                        <option value="">Data not found.</option>
                                                                                                    @endif
                                                                                                @endif
                                                                                            </select>
                                                                                        @elseif($question->remasking_enable ==1)
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
                                                                                            <select  class="form-control myClassName" name="answer_option_group_value[]_{{$question->id}}">
                                                                                                @if(!empty($new_array) && count($new_array) > 0)
                                                                                                    @foreach($new_array as $key=>$val)
                                                                                                        <option value="{{$key}}">{{$val}}</option>
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                        @foreach($option_groups as $key=>$group)
                                                                                                            <option value="{{ $group->id }}">{{$group->question_option_name}}</option>
                                                                                                        @endforeach
                                                                                                    @else
                                                                                                        <option value="">Data not found.</option>
                                                                                                    @endif
                                                                                                @endif
                                                                                            </select>
                                                                                        @else
                                                                                            <select  class="form-control myClassName" name="answer_option_group_value[]_{{$question->id}}">
                                                                                                @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                    @foreach($option_groups as $key=>$group)
                                                                                                        <option value="{{ $group->id }}">{{$group->question_option_name}}</option>
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    <option value="">Data not found.</option>
                                                                                                @endif
                                                                                            </select>
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
                                                                                            <select  class="form-control" name="answer_option_group_value[]_{{$question->id}}">
                                                                                                @if(!empty($new_array) && count($new_array) > 0)
                                                                                                    @foreach($new_array as $key=>$val)
                                                                                                        <option value="{{$key}}">{{$val}}</option>
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                        @foreach($option_groups as $key=>$group)
                                                                                                            <option value="{{ $group->id }}">{{$group->question_option_name}}</option>
                                                                                                        @endforeach
                                                                                                    @else
                                                                                                        <option value="">Data not found.</option>
                                                                                                    @endif
                                                                                                @endif
                                                                                            </select>
                                                                                        @elseif($question->remasking_enable ==1)
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
                                                                                            <select  class="form-control" name="answer_option_group_value[]_{{$question->id}}">
                                                                                                @if(!empty($new_array) && count($new_array) > 0)
                                                                                                    @foreach($new_array as $key=>$val)
                                                                                                        <option value="{{$key}}">{{$val}}</option>
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                        @foreach($option_groups as $key=>$group)
                                                                                                            <option value="{{ $group->id }}">{{$group->question_option_name}}</option>
                                                                                                        @endforeach
                                                                                                    @else
                                                                                                        <option value="">Data not found.</option>
                                                                                                    @endif
                                                                                                @endif
                                                                                            </select>
                                                                                        @else
                                                                                            <select  class="form-control" name="answer_option_group_value[]_{{$question->id}}">
                                                                                                @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                    @foreach($option_groups as $key=>$group)
                                                                                                        <option value="{{ $group->id }}">{{$group->question_option_name}}</option>
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    <option value="">Data not found.</option>
                                                                                                @endif
                                                                                            </select>
                                                                                        @endif
                                                                                    @endif
                                                                                @elseif($question->question_input_type_id == 4)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    <table width="100%">
                                                                                        @if($question->question_answer_require == 1 )
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <strong>Option - {{ $key+1 }}:</strong>  {{$group->question_option_name}}
                                                                                                            <?php
                                                                                                            $question_option_group_details = \DB::table('question_option_group_details')
                                                                                                                ->where('question_option_group_id',$group->id)
                                                                                                                ->get();
                                                                                                            ?>
                                                                                                            @if(!empty($question_option_group_details) && count($question_option_group_details) > 0)
                                                                                                                <table>
                                                                                                                    <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                                    @foreach($question_option_group_details as $key => $question_option_group_detail)
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                L-{{$key+1}}:
                                                                                                                                <label class="btn btn-xs btn-default btn-squared">
                                                                                                                                    <input type="radio" value="{{$question_option_group_detail->o_value}}"
                                                                                                                                           name="user_answer[]_{{$question_option_group_detail->question_option_group_id}}"
                                                                                                                                           id="option{{$question_option_group_detail->o_value}}" class="myClassName">
                                                                                                                                    {{$question_option_group_detail->o_name}}
                                                                                                                                </label>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @endforeach
                                                                                                                </table>
                                                                                                            @else
                                                                                                                <p>information not found</p>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @else
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <strong>Option - {{ $key+1 }}:</strong>  {{$group->question_option_name}}
                                                                                                            <?php
                                                                                                            $question_option_group_details = \DB::table('question_option_group_details')
                                                                                                                ->where('question_option_group_id',$group->id)
                                                                                                                ->get();
                                                                                                            ?>
                                                                                                            @if(!empty($question_option_group_details) && count($question_option_group_details) > 0)
                                                                                                                <table>
                                                                                                                    <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                                    @foreach($question_option_group_details as $key => $question_option_group_detail)
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                L-{{$key+1}}:
                                                                                                                                <label class="btn btn-xs btn-default btn-squared">
                                                                                                                                    {{--<input type="hidden" name="user_answer[]" value="{{$question_option_group_detail->o_name}}">--}}
                                                                                                                                    <input type="radio" value="{{$question_option_group_detail->o_value}}"
                                                                                                                                           name="user_answer[]_{{$question_option_group_detail->question_option_group_id}}"
                                                                                                                                           id="option{{$question_option_group_detail->o_value}}">
                                                                                                                                    {{$question_option_group_detail->o_name}}
                                                                                                                                </label>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @endforeach
                                                                                                                </table>
                                                                                                            @else
                                                                                                                <p>information not found</p>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endif
                                                                                    </table>
                                                                                @elseif($question->question_input_type_id == 5)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    <table width="100%">
                                                                                        @if($question->question_answer_require == 1 )
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            <input type="text" class="form-control myClassName" name="user_answer[]">
                                                                                                        </td>>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @else
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            <input type="text" class="form-control" name="user_answer[]">
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endif
                                                                                    </table>
                                                                                @elseif($question->question_input_type_id == 6)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    <table>
                                                                                        @if($question->question_answer_require == 1 )
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            <textarea class="form-control myClassName" name="user_answer[]"></textarea></td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @else
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            <textarea class="form-control" name="user_answer[]"></textarea>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endif
                                                                                    </table>
                                                                                @elseif($question->question_input_type_id == 7)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    <table width="100%">
                                                                                        @if($question->question_answer_require == 1 )
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <label><strong>{{$key+1}} : {{$group->question_option_name}}</strong></label>
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            <input type="text" class="form-control myClassName" name="user_answer[]">
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @else
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <label>
                                                                                                                <strong>{{$key+1}} : {{$group->question_option_name}}</strong>
                                                                                                            </label>
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            <input type="text" class="form-control" name="user_answer[]">
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endif
                                                                                    </table>
                                                                                @elseif($question->question_input_type_id == 8)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    <table width="100%">
                                                                                        @if($question->question_answer_require == 1 )
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            {{--<label><strong>{{$key+1}} : {{$group->question_option_name}}</strong></label>--}}
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">

                                                                                                            <input type="text" data-question-option-group-id="{{$group->id}}" data-max="{{ $group->max }}" data-min="{{ $group->min }}"
                                                                                                                   class="form-control myClassName number_check check_maxMin" name="user_answer[]" placeholder="Only number are allowed" >
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @else
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            {{--<label><strong>{{$key+1}} : {{$group->question_option_name}}</strong></label>--}}
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            <input type="text" data-question-option-group-id="{{$group->id}}" data-max="{{ $group->max }}" data-min="{{ $group->min }}"
                                                                                                                   class="form-control number_check check_maxMin" name="user_answer[]" placeholder="Only number are allowed" >
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endif
                                                                                    </table>
                                                                                @elseif($question->question_input_type_id == 9)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    <table width="100%">
                                                                                        @if($question->question_answer_require == 1 )
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            {{--<label><strong>{{$key+1}} : {{$group->question_option_name}} (%) </strong></label>--}}
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            <input type="text" data-question-option-group-id="{{$group->id}}" data-max="{{ $group->max }}" data-min="{{ $group->min }}"
                                                                                                                   class="form-control percentage_check check_maxMin" name="user_answer[]" placeholder="how many percentage ?" >
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @else
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            {{-- <label><strong>{{$key+1}} : {{$group->question_option_name}} (%) </strong></label>--}}
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            <input type="text" data-question-option-group-id="{{$group->id}}" data-max="{{ $group->max }}" data-min="{{ $group->min }}"
                                                                                                                   class="form-control percentage_check check_maxMin" name="user_answer[]" placeholder="how many percentage ?" >

                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endif
                                                                                    </table>
                                                                                @elseif($question->question_input_type_id == 10)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    <table width="100%">
                                                                                        @if($question->question_answer_require == 1 )
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <strong>Option - {{ $key+1 }}:</strong>  {{$group->question_option_name}}
                                                                                                            <?php
                                                                                                            $question_option_group_details = \DB::table('question_option_group_details')
                                                                                                                ->where('question_option_group_id',$group->id)
                                                                                                                ->get();
                                                                                                            ?>
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            @if(!empty($question_option_group_details) && count($question_option_group_details) > 0)
                                                                                                                <select class="form-control myClassName pull-left" name="user_answer[]">
                                                                                                                    <option value="">Select scale</option>
                                                                                                                    @foreach($question_option_group_details as $key => $question_option_group_detail)
                                                                                                                        <option value="{{$question_option_group_detail->o_value}}">{{$question_option_group_detail->o_name}}</option>
                                                                                                                    @endforeach
                                                                                                                </select>
                                                                                                            @else
                                                                                                                <p>information not found</p>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @else
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <strong>Option - {{ $key+1 }}:</strong>  {{$group->question_option_name}}
                                                                                                            <?php
                                                                                                            $question_option_group_details = \DB::table('question_option_group_details')
                                                                                                                ->where('question_option_group_id',$group->id)
                                                                                                                ->get();
                                                                                                            ?>
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            @if(!empty($question_option_group_details) && count($question_option_group_details) > 0)
                                                                                                                <select class="form-control pull-left" name="user_answer[]">
                                                                                                                    <option value="">Select scale</option>
                                                                                                                    @foreach($question_option_group_details as $key => $question_option_group_detail)
                                                                                                                        <option value="{{$question_option_group_detail->o_value}}">{{$question_option_group_detail->o_name}}</option>
                                                                                                                    @endforeach
                                                                                                                </select>
                                                                                                            @else
                                                                                                                <p>information not found</p>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endif
                                                                                    </table>
                                                                                @elseif($question->question_input_type_id == 11)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    <table width="100%">
                                                                                        @if($question->question_answer_require == 1 )
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td width="93%">
                                                                                                            <label><strong>{{$key+1}} : {{$group->question_option_name}}</strong></label><br>
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            @if(is_numeric($group->question_option_value))
                                                                                                                <fieldset class="rating">
                                                                                                                    @for ($i = 1; $i <=$group->question_option_value; $i++)
                                                                                                                        <span class="star"></span>
                                                                                                                        <input type="radio" id="star{{$i}}{{$group->id}}" class="myClassName" name="user_answer[]" value="{{ $i }}" />
                                                                                                                        <label for="star{{$i}}{{$group->id}}" >{{$i}} stars</label>
                                                                                                                    @endfor
                                                                                                                </fieldset>
                                                                                                            @else
                                                                                                                <strong>Option value must be a number</strong>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @else
                                                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                                @foreach($option_groups as $key=>$group)
                                                                                                    <tr>
                                                                                                        <td width="93%">
                                                                                                            <label><strong>{{$key+1}} : {{$group->question_option_name}}</strong></label><br>
                                                                                                            <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                            @if(is_numeric($group->question_option_value))
                                                                                                                <fieldset class="rating">
                                                                                                                    @for ($i = $group->question_option_value;$i >=1; $i--)
                                                                                                                        <span class="star"></span>
                                                                                                                        <input type="radio" id="star{{$i}}{{$group->id}}" name="user_answer[]_{{$group->id}}" value="{{ $i }}" />
                                                                                                                        <label for="star{{$i}}{{$group->id}}" >{{$i}} stars</label>
                                                                                                                    @endfor
                                                                                                                </fieldset>
                                                                                                            @else
                                                                                                                <strong>Option value must be a number</strong>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td>Option not found</td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endif
                                                                                    </table>
                                                                                @elseif($question->question_input_type_id == 12)
                                                                                    <?php
                                                                                    $option_groups = \DB::table('question_option_group')
                                                                                        ->where('option_question_id',$question->id)
                                                                                        ->where('option_input_type_id',$question->question_input_type_id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    <ul class="list-group example" style="list-style: none">
                                                                                        @if(!empty($option_groups) && count($option_groups) > 0)
                                                                                            @foreach($option_groups as $key=>$group)
                                                                                                <li class="list-group-item" style="cursor: pointer; border-radius: 0px;">
                                                                                                    {{$group->question_option_name}}
                                                                                                    <input type="hidden" name="answer_option_group_value[]_{{$question->id}}" value="{{$group->id}}">
                                                                                                    <input type="hidden" value="{{$group->question_option_value}}"
                                                                                                           name="user_answer[]_{{$question->id}}">
                                                                                                </li>
                                                                                            @endforeach
                                                                                        @else
                                                                                            <li>Option Not found</li>
                                                                                        @endif
                                                                                    </ul>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endif
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
                                    @if($questionInput->question_input_type_id == 14)
                                        <div class="form-group" style="padding-right: 10px;">
                                            <input class="btn btn-success  btn-squared pull-right" name="submit" value="Finish" type="submit">
                                        </div>
                                    @else
                                        <div class="form-group" style="padding-right: 10px;">
                                            <input class="btn btn-success  btn-squared pull-right" name="submit" value="Next" type="submit">
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('JScript')
    <script>
        $(function () {
            var site_url = $('.site_url').val();
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
            $("ul.example").sortable();
            $('.number_check').on('keydown',function(e){
                var deleteKeyCode = 8;
                var backspaceKeyCode = 46;
                if ((e.which>=48 && e.which<=57)
                    || (e.which>=96 && e.which<=105)
                    || e.which === deleteKeyCode
                    || e.which === backspaceKeyCode)
                {
                    // $(this).removeClass('has-error');
                    return true;
                } else {
                    //$(this).addClass('has-error');
                    return false;
                }
            });
            $('.percentage_check').on('keydown',function(event){
                if (event.shiftKey == true) {
                    event.preventDefault();
                }

                if ((event.keyCode >= 48 && event.keyCode <= 57)
                    || (event.keyCode >= 96 && event.keyCode <= 105)
                    || event.keyCode == 8 || event.keyCode == 9
                    || event.keyCode == 37 || event.keyCode == 39
                    || event.keyCode == 46 || event.keyCode == 190) {

                } else {
                    event.preventDefault();
                }
                if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                    event.preventDefault();
            });

            $('.check_maxMin').keypress( _.debounce( function(){
                var id = $(this).data('question-option-group-id');
                var value = $(this).val();
                var max = $(this).data('max');
                var min = $(this).data('min');
                $.ajax({
                    Type: "GET",
                    url: site_url + '/check/max/min/'+id+'/'+value,
                    success: function (data) {
                        if(data.status == "notFound") {
                            alert('Something is wrong');
                            $('.check_maxMin').val('')
                        } else if(data.status == "max") {
                            alert('maximum allowed : ' + max);
                            $('.check_maxMin').val('')
                        } else if (data.status == "min") {
                            alert('minimum allowed : ' + min);
                            $('.check_maxMin').val('')
                        } else {

                        }
                    },
                });
            }, 500 ) );
        })
    </script>
@endsection