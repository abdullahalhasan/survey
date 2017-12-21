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
                <a href="javascript:void(0);" data-campaign-id="{{$campaign_id}}" class=" btn btn-primary btn-squared add-more-question">
                    <i class="fa fa-plus"></i> Add more question
                </a>
                <a href="javascript:void(0);" data-campaign-id="{{$campaign_id}}" class=" btn btn-info btn-squared add-branch-question">
                    <i class="fa fa-plus"></i> Add Branch
                </a>
                <a href="{{ url('admin/survey/campaign') }}" class=" btn btn-success btn-squared ">
                    <i class="fa fa-bar-chart-o"></i> All campaign
                </a>
            </div>
            @if(!empty($page_numbers) && count($page_numbers)>0)
                @foreach($page_numbers as $page_number)
                    <div class="panel panel-default btn-squared">
                        <div class="panel-heading">
                            <i class="fa fa-external-link-square"></i>
                            Questions
                            <div class="panel-tools">
                                <strong>Page number : {{ $page_number->question_page_no }}</strong>
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
                                    $questions = \App\Question::getAllSurveyQuestionByPageNumber($page_number->question_page_no,$campaign_id)
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table">
                                            @if(!empty($questions) && count($questions)>0)
                                                @foreach($questions as $key => $question)
                                                    <tr>
                                                        <td width="85%">
                                                            <strong>
                                                                <a href="{{ url('admin/question/edit/'.$question->campaign_id.'/'.$question->id) }}" title="Edit"> Q - {{ $key+1 }}</a> :
                                                                {{ isset($question->question_title) ? $question->question_title: '' }}
                                                            </strong>
                                                            <span class="help-block">{{ isset($question->question_help_text) ? 'Help text: '.$question->question_help_text:'' }}</span>
                                                            @if($question->question_input_type_id == 1)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                ?>
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
                                                            @elseif($question->question_input_type_id == 2)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                ?>
                                                                @foreach($option_groups as $key=>$group)
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                            <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                            {{$group->question_option_name}}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            @elseif($question->question_input_type_id == 3)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                ?>
                                                                <select  class="form-control" name="">
                                                                    @foreach($option_groups as $key=>$group)
                                                                        <option value="{{ $group->id }}">{{$group->question_option_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @elseif($question->question_input_type_id == 5)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                ?>
                                                                @foreach($option_groups as $key=>$group)
                                                                    <input type="text" class="form-control" name="">
                                                                @endforeach
                                                            @elseif($question->question_input_type_id == 6)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                ?>
                                                                @foreach($option_groups as $key=>$group)
                                                                   <textarea class="form-control"></textarea>
                                                                @endforeach
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
                @endforeach
            @else
                <div class="panel panel-default btn-squared">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i>
                        Questions
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
                            <div class="col-md-12">
                                <strong>Question not found.</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!--modal for question -->
    <div id="ajax-more-question" class="modal fade" tabindex="-1" style="display: none;" data-width="65%"></div>
    <!--modal for question -->
    <!--modal for branch-->
    <div id="ajax-branch-question" class="modal fade" tabindex="-1" style="display: none;" data-width="60%"></div>
    <!--modal for branch -->
@endsection
