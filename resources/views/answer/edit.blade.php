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
                                <div class="table-responsive">
                                    <table class="table">
                                        @if(!empty($questions) && count($questions)>0)
                                            @foreach($questions as $key => $question)
                                                <?php
                                                $maskingQuestion = \DB::table('question_meta')
                                                    ->where('meta_question_field_name','masked_question_id')
                                                    ->where('question_id',$question->id)
                                                    ->first();
                                                ?>
                                                <tr>
                                                    <td width="85%">
                                                        <strong>
                                                            Question - {{ $key+1 }} :
                                                            {{ isset($question->question_title) ? $question->question_title: '' }}
                                                        </strong>
                                                        <span class="help-block">{{ isset($question->question_help_text) ? 'Help text: '.$question->question_help_text:'' }}</span>
                                                        <input type="hidden" name="page_number" value="{{$question->question_page_no}}">
                                                        @if($question->question_answer_require == 1)
                                                            <?php
                                                            $option_groups = \DB::table('question_option_group')
                                                                ->where('option_question_id',$question->id)
                                                                ->where('option_input_type_id',$question->question_input_type_id)
                                                                ->get();
                                                            ?>
                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                @foreach($option_groups as $key=>$group)
                                                                    @if($group->option_input_type_id == 1)
                                                                        <div class="radio">
                                                                            <label>
                                                                                <input type="radio" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey myClassName">
                                                                                {{$group->question_option_name}}
                                                                                <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                            </label>
                                                                        </div>
                                                                    @elseif($group->option_input_type_id == 2)
                                                                        <div class="checkbox">
                                                                            <label>
                                                                                <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                <input type="checkbox"  value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey myClassName">
                                                                                {{$group->question_option_name}}
                                                                            </label>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <p><strong>Option not found.</strong></p>
                                                            @endif
                                                        @else
                                                            <?php
                                                            $option_groups = \DB::table('question_option_group')
                                                                ->where('option_question_id',$question->id)
                                                                ->where('option_input_type_id',$question->question_input_type_id)
                                                                ->get();
                                                            ?>
                                                            @if(!empty($option_groups) && count($option_groups) > 0)
                                                                @foreach($option_groups as $key=>$group)
                                                                    @if($group->option_input_type_id == 1)
                                                                        <div class="radio">
                                                                            <label>
                                                                                <input type="radio" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                {{$group->question_option_name}}
                                                                                <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                            </label>
                                                                        </div>
                                                                    @elseif($group->option_input_type_id == 2)
                                                                        <div class="checkbox">
                                                                            <label>
                                                                                <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                {{$group->question_option_name}}
                                                                            </label>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <p><strong>Option not found.</strong></p>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if(count($maskingQuestion) > 0)
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
                submitHandler: function(form) {
                    // do other things for a valid form
                    form.submit();
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).closest("div").addClass("ym-error");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).closest("div").removeClass("ym-error");
                },
                ignore: ".ignore"
            });
            jQuery.validator.addClassRules('myClassName', {
                required: true /*,
                 other rules */
            });
        })
    </script>
@endsection
