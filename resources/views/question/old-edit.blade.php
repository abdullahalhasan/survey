@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="panel panel-default btn-squared">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i>
                    Edit Question
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
                                <a href="{{ url('admin/survey/campaign') }}" class=" btn btn-success btn-squared ">
                                    <i class="fa fa-bar-chart-o"></i> All campaign
                                </a>
                            </div>
                        </div>
                    </div>
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
                            <form action="{{ url('admin/question/update/'.$campaign_id.'/'.$question->id) }}" role="form" class="question-form" method="post">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Question input type</strong>
                                    </label>
                                    <select  class="input-type form-control search-select" name="question_input_type_id">
                                        <option value="">&nbsp;Choice Question Type</option>
                                        @if(!empty($input_types) && count($input_types) > 0)
                                            @foreach($input_types as $input_type)
                                                <option {{($question->question_input_type_id == $input_type->id) ? "selected" :''}}
                                                        value="{{ $input_type->id }}">
                                                    {{$input_type->input_type_name}}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="">Data not found.</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Question Name </strong>
                                    </label>
                                    <input class="form-control" name="question_title" value="{{$question->question_title}}"  type="text"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        <strong>Question Help Text </strong>
                                    </label>
                                    <input class="form-control" name="question_help_text" value="{{$question->question_help_text}}"  type="text" />
                                </div>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Option name and value</legend>
                                    <div class="table-responsive">
                                        <table class="table" id="customFields">
                                            @if(!empty($question_option_group) &&count($question_option_group) > 0)
                                                @foreach($question_option_group as $group)
                                                    <tr>
                                                        <td>
                                                            <div class="col-md-5">
                                                                <input type="text" class="form-control" value="{{$group->question_option_name}}" name="question_option_name[]" />
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" class="form-control" value="{{$group->question_option_value}}" name="question_option_value[]" />
                                                            </div>
                                                            <div class="col-md-2">
                                                                <a href="{{ url('admin/question/option/group/delete/'.$group->id) }}" class="btn btn-danger btn-squared"><i class="fa fa-minus"></i> Remove</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared" id="add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"  name="question_option_name[]" placeholder=" Option name" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"   name="question_option_value[]" placeholder=" Option value" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared" id="add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </fieldset>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" <?php if ($question->question_answer_require == 1) { echo 'checked="checked"'; } ?>
                                            value="{{ isset($question->question_answer_require)? $question->question_answer_require : 1 }}"
                                                   class="grey" name="question_answer_require">
                                            Question answer required ?
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" <?php if ($question->masking_enable == 1) { echo 'checked="checked"'; } ?>
                                            class="grey" value="{{ isset($question->masking_enable)? $question->masking_enable : 1 }}" name="masking_enable">
                                            Masking enable ?
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" <?php if ($question->branching_enable == 1) { echo 'checked="checked"'; } ?> class="grey"
                                                   value="{{ isset($question->branching_enable)? $question->branching_enable : 1 }}" name="branching_enable">
                                            Branch enable ?
                                        </label>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p>
                                                By clicking create QUESTION.
                                            </p>
                                        </div>
                                        <div class="col-md-2">
                                            <p style="text-align: right">
                                                Page Number
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="question_page_no" value="{{$question->question_page_no}}">
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-yellow btn-block btn-squared" type="submit">
                                                Update question <i class="fa fa-arrow-circle-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end: FORM VALIDATION 2 PANEL -->
        </div>
    </div>
@endsection

@section('JScript')
    <script>
        $(function () {
            var site_url = $('.site_url').val();
            $('.question-form').validate({
                rules: {
                    campaign_id: {
                        required: true
                    },
                    question_title: {
                        required: true
                    },
                    question_page_no: {
                        required: true
                    },
                    question_option_name: {
                        required: true
                    },
                    question_option_value: {
                        required: true
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


            $("#add-more-option").click(function(){
                $("#customFields").append(
                    '<tr>' +
                    '<td>' +
                    '<div class="col-md-5">'+
                    '<input type="text" class="form-control"  name="question_option_name[]" placeholder=" Option name" />'+
                    '</div>'+
                    '<div class="col-md-5">'+
                    '<input type="text" class="form-control"  name="question_option_value[]" placeholder=" Option value" />'+
                    '</div>'+
                    '<div class="col-md-2">'+
                    '<a href="javascript:void(0);" class="btn btn-danger btn-squared remove-option"><i class="fa fa-minus"></i> Remove</a>'+
                    '</div>'+
                    '</td>' +
                    '</tr>'
                );
                $(".remove-option").on('click',function(){
                    $(this).parent().parent().remove();
                });
            });
        })
    </script>
@endsection