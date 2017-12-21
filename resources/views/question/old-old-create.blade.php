@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="panel panel-default btn-squared">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i>
                    Create Question
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
                            <div class="form-group">
                                <label class="control-label">
                                    <strong>Question input type</strong>
                                </label>
                                <select  class="input-type form-control search-select" name="">
                                    <option value="">&nbsp;Choice Question Type</option>
                                    @if(!empty($input_types) && count($input_types) > 0)
                                        @foreach($input_types as $input_type)
                                            <option value="{{ $input_type->input_type_value }}">{{$input_type->input_type_name}}</option>
                                        @endforeach
                                    @else
                                        <option value="">Data not found.</option>
                                    @endif
                                </select>
                            </div>
                            <div class="single_choice">
                                <form action="{{ url('admin/question/store') }}" role="form" class="question-form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                                    <input type="hidden" name="question_input_type_id" class="question-input-type">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Name </strong>
                                        </label>
                                        <input class="form-control" name="question_title"  type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Help Text </strong>
                                        </label>
                                        <input class="form-control" name="question_help_text"  type="text" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Single Choice</legend>
                                        <div class="table-responsive">
                                            <table class="table customFields">
                                                <tr>
                                                    <td>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"   name="question_option_value[]" placeholder="Value" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" name="question_answer_require">
                                                Question answer required ?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                                                            Masking enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="show-masking">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="branching" name="branching_enable">
                                                            Branch enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="enable-branching">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
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
                                                <p style="text-align: right; padding-top: 8px;">
                                                    Page Number
                                                </p>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="question_page_no">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-yellow btn-block btn-squared" type="submit">
                                                    Create question <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="multiple_choice">
                                <form action="{{ url('admin/question/store') }}" role="form" class="question-form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                                    <input type="hidden" name="question_input_type_id" class="question-input-type">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Name </strong>
                                        </label>
                                        <input class="form-control" name="question_title"  type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Help Text </strong>
                                        </label>
                                        <input class="form-control" name="question_help_text"  type="text" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Multiple Choice</legend>
                                        <div class="table-responsive">
                                            <table class="table customFields">
                                                <tr>
                                                    <td>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"   name="question_option_value[]" placeholder="Value" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" class="grey" name="question_answer_require">
                                                Question answer required ?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                                                            Masking enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="show-masking">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="branching" name="branching_enable">
                                                            Branch enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="enable-branching">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
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
                                                <input type="text" class="form-control" name="question_page_no">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-yellow btn-block btn-squared" type="submit">
                                                    Create question <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="select_box">
                                <form action="{{ url('admin/question/store') }}" role="form" class="question-form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                                    <input type="hidden" name="question_input_type_id" class="question-input-type">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Name </strong>
                                        </label>
                                        <input class="form-control" name="question_title"  type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Help Text </strong>
                                        </label>
                                        <input class="form-control" name="question_help_text"  type="text" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Select Box</legend>
                                        <div class="table-responsive">
                                            <table class="table customFields">
                                                <tr>
                                                    <td>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"   name="question_option_value[]" placeholder="Value" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" class="grey" name="question_answer_require">
                                                Question answer required ?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                                                            Masking enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="show-masking">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="branching" name="branching_enable">
                                                            Branch enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="enable-branching">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
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
                                                <input type="text" class="form-control" name="question_page_no">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-yellow btn-block btn-squared" type="submit">
                                                    Create question <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="button_choice">
                                <form action="{{ url('admin/question/store') }}" role="form" class="question-form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                                    <input type="hidden" name="question_input_type_id" class="question-input-type">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Name </strong>
                                        </label>
                                        <input class="form-control" name="question_title"  type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Help Text </strong>
                                        </label>
                                        <input class="form-control" name="question_help_text"  type="text" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Button Choice</legend>
                                        <div class="table-responsive">
                                            <table class="table customFields">
                                                <tr>
                                                    <td>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"   name="question_option_value[]" placeholder="Value" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" class="grey" name="question_answer_require">
                                                Question answer required ?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                                                            Masking enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="show-masking">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="branching" name="branching_enable">
                                                            Branch enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="enable-branching">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
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
                                                <input type="text" class="form-control" name="question_page_no">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-yellow btn-block btn-squared" type="submit">
                                                    Create question <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="single_line">
                                <form action="{{ url('admin/question/store') }}" role="form" class="question-form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                                    <input type="hidden" name="question_input_type_id" class="question-input-type">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Name </strong>
                                        </label>
                                        <input class="form-control" name="question_title"  type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Help Text </strong>
                                        </label>
                                        <input class="form-control" name="question_help_text"  type="text" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Single Line</legend>
                                        <div class="table-responsive">
                                            <table class="table customFields">
                                                <tr>
                                                    <td>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"   name="question_option_value[]" placeholder="Value" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" class="grey" name="question_answer_require">
                                                Question answer required ?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                                                            Masking enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="show-masking">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="branching" name="branching_enable">
                                                            Branch enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="enable-branching">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
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
                                                <input type="text" class="form-control" name="question_page_no">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-yellow btn-block btn-squared" type="submit">
                                                    Create question <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="multiple_line">
                                <form action="{{ url('admin/question/store') }}" role="form" class="question-form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                                    <input type="hidden" name="question_input_type_id" class="question-input-type">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Name </strong>
                                        </label>
                                        <input class="form-control" name="question_title"  type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Help Text </strong>
                                        </label>
                                        <input class="form-control" name="question_help_text"  type="text" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Multiple Line</legend>
                                        <div class="table-responsive">
                                            <table class="table customFields">
                                                <tr>
                                                    <td>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"   name="question_option_value[]" placeholder="Value" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" class="grey" name="question_answer_require">
                                                Question answer required ?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                                                            Masking enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="show-masking">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="branching" name="branching_enable">
                                                            Branch enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="enable-branching">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
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
                                                <input type="text" class="form-control" name="question_page_no">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-yellow btn-block btn-squared" type="submit">
                                                    Create question <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="multiple_field">
                                <form action="{{ url('admin/question/store') }}" role="form" class="question-form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                                    <input type="hidden" name="question_input_type_id" class="question-input-type">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Name </strong>
                                        </label>
                                        <input class="form-control" name="question_title"  type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Help Text </strong>
                                        </label>
                                        <input class="form-control" name="question_help_text"  type="text" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Multiple Field</legend>
                                        <div class="table-responsive">
                                            <table class="table customFields">
                                                <tr>
                                                    <td>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"   name="question_option_value[]" placeholder="Value" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" class="grey" name="question_answer_require">
                                                Question answer required ?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                                                            Masking enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="show-masking">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="branching" name="branching_enable">
                                                            Branch enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="enable-branching">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
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
                                                <input type="text" class="form-control" name="question_page_no">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-yellow btn-block btn-squared" type="submit">
                                                    Create question <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="number">
                                <form action="{{ url('admin/question/store') }}" role="form" class="question-form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                                    <input type="hidden" name="question_input_type_id" class="question-input-type">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Name </strong>
                                        </label>
                                        <input class="form-control" name="question_title"  type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Help Text </strong>
                                        </label>
                                        <input class="form-control" name="question_help_text"  type="text" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Number</legend>
                                        <div class="table-responsive">
                                            <table class="table customFields">
                                                <tr>
                                                    <td>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"   name="question_option_value[]" placeholder="Value" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" class="grey" name="question_answer_require">
                                                Question answer required ?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                                                            Masking enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="show-masking">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="branching" name="branching_enable">
                                                            Branch enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="enable-branching">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
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
                                                <input type="text" class="form-control" name="question_page_no">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-yellow btn-block btn-squared" type="submit">
                                                    Create question <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="percentage">
                                <form action="{{ url('admin/question/store') }}" role="form" class="question-form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                                    <input type="hidden" name="question_input_type_id" class="question-input-type">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Name </strong>
                                        </label>
                                        <input class="form-control" name="question_title"  type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Help Text </strong>
                                        </label>
                                        <input class="form-control" name="question_help_text"  type="text" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Percentage</legend>
                                        <div class="table-responsive">
                                            <table class="table customFields">
                                                <tr>
                                                    <td>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"   name="question_option_value[]" placeholder="Value" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" class="grey" name="question_answer_require">
                                                Question answer required ?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                                                            Masking enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="show-masking">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="branching" name="branching_enable">
                                                            Branch enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="enable-branching">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
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
                                                <input type="text" class="form-control" name="question_page_no">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-yellow btn-block btn-squared" type="submit">
                                                    Create question <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="scale">
                                <form action="{{ url('admin/question/store') }}" role="form" class="question-form" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
                                    <input type="hidden" name="question_input_type_id" class="question-input-type">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Name </strong>
                                        </label>
                                        <input class="form-control" name="question_title"  type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">
                                            <strong>Question Help Text </strong>
                                        </label>
                                        <input class="form-control" name="question_help_text"  type="text" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Scale</legend>
                                        <div class="table-responsive">
                                            <table class="table customFields">
                                                <tr>
                                                    <td>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"   name="question_option_value[]" placeholder="Value" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option"><i class="fa fa-plus"></i> Add more option</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" class="grey" name="question_answer_require">
                                                Question answer required ?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                                                            Masking enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="show-masking">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="1" class="branching" name="branching_enable">
                                                            Branch enable ?
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="40%">
                                                    <div class="enable-branching">
                                                        <select  class="form-control search-select" name="meta_question_field_value">
                                                            <option value="">&nbsp;Choice Question Name</option>
                                                            @if(!empty($questions) && count($questions) > 0)
                                                                @foreach($questions as $question)
                                                                    <option value="{{ $question->id }}">{{$question->question_title}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="">Question Not Found.</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">&nbsp;</td>
                                            </tr>
                                        </table>
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
                                                <input type="text" class="form-control" name="question_page_no">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-yellow btn-block btn-squared" type="submit">
                                                    Create question <i class="fa fa-arrow-circle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

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
            $('.single_choice').hide();
            $('.multiple_choice').hide();
            $('.select_box').hide();
            //new
            $('.button_choice').hide();
            $('.single_line').hide();
            $('.multiple_line').hide();
            $('.multiple_field').hide();
            $('.number').hide();
            $('.percentage').hide();
            $('.scale').hide();
            $('.start_rating').hide();
            $('.ranking').hide();
            //enable-masking
            $('.show-masking').hide();
            // enable barnching
            $('.enable-branching').hide();

            $('.input-type').on('change', function () {
                var val = $(this).val();
                var id = $(this).data('input-type-id')
                if(val == '') {
                    return;
                }
                switch(val) {
                    case 'single_choice':
                        $('.single_choice').show();
                        $('.multiple_choice').hide();
                        $('.select_box').hide();
                        $('.button_choice').hide();
                        $('.single_line').hide();
                        $('.multiple_line').hide();
                        $('.multiple_field').hide();
                        $('.number').hide();
                        $('.percentage').hide();
                        $('.scale').hide();
                        $('.start_rating').hide();
                        $('.ranking').hide();
                        $('.question-input-type').val(1);
                        break;
                    case 'multiple_choice':
                        $('.multiple_choice').show();
                        $('.single_choice').hide();
                        $('.select_box').hide();
                        $('.button_choice').hide();
                        $('.single_line').hide();
                        $('.multiple_line').hide();
                        $('.multiple_field').hide();
                        $('.number').hide();
                        $('.percentage').hide();
                        $('.scale').hide();
                        $('.start_rating').hide();
                        $('.ranking').hide();
                        $('.question-input-type').val(2);
                        break;
                    case 'select_box':
                        $('.select_box').show();
                        $('.single_choice').hide();
                        $('.multiple_choice').hide();
                        $('.button_choice').hide();
                        $('.single_line').hide();
                        $('.multiple_line').hide();
                        $('.multiple_field').hide();
                        $('.number').hide();
                        $('.percentage').hide();
                        $('.scale').hide();
                        $('.start_rating').hide();
                        $('.ranking').hide();
                        $('.question-input-type').val(3);
                        break;
                    case 'button_choice':
                        $('.button_choice').show();
                        $('.select_box').hide();
                        $('.single_choice').hide();
                        $('.multiple_choice').hide();
                        $('.single_line').hide();
                        $('.multiple_line').hide();
                        $('.multiple_field').hide();
                        $('.number').hide();
                        $('.percentage').hide();
                        $('.scale').hide();
                        $('.start_rating').hide();
                        $('.ranking').hide();
                        $('.question-input-type').val(4);
                        break;
                    case 'single_line':
                        $('.single_line').show();
                        $('.button_choice').hide();
                        $('.select_box').hide();
                        $('.single_choice').hide();
                        $('.multiple_choice').hide();
                        $('.multiple_line').hide();
                        $('.multiple_field').hide();
                        $('.number').hide();
                        $('.percentage').hide();
                        $('.scale').hide();
                        $('.start_rating').hide();
                        $('.ranking').hide();
                        $('.question-input-type').val(5);
                        break;
                    case 'multiple_line':
                        $('.multiple_line').show();
                        $('.button_choice').hide();
                        $('.select_box').hide();
                        $('.single_choice').hide();
                        $('.multiple_choice').hide();
                        $('.single_line').hide();
                        $('.multiple_field').hide();
                        $('.number').hide();
                        $('.percentage').hide();
                        $('.scale').hide();
                        $('.start_rating').hide();
                        $('.ranking').hide();
                        $('.question-input-type').val(6);
                        break;
                    case 'multiple_field':
                        $('.multiple_field').show();
                        $('.button_choice').hide();
                        $('.select_box').hide();
                        $('.single_choice').hide();
                        $('.multiple_choice').hide();
                        $('.single_line').hide();
                        $('.multiple_line').hide();
                        $('.number').hide();
                        $('.percentage').hide();
                        $('.scale').hide();
                        $('.start_rating').hide();
                        $('.ranking').hide();
                        $('.question-input-type').val(7);
                        break;
                    case 'number':
                        $('.number').show();
                        $('.button_choice').hide();
                        $('.select_box').hide();
                        $('.single_choice').hide();
                        $('.multiple_choice').hide();
                        $('.single_line').hide();
                        $('.multiple_line').hide();
                        $('.multiple_field').hide();
                        $('.percentage').hide();
                        $('.scale').hide();
                        $('.start_rating').hide();
                        $('.ranking').hide();
                        $('.question-input-type').val(8);
                        break;
                    case 'percentage':
                        $('.percentage').show();
                        $('.button_choice').hide();
                        $('.select_box').hide();
                        $('.single_choice').hide();
                        $('.multiple_choice').hide();
                        $('.single_line').hide();
                        $('.multiple_line').hide();
                        $('.multiple_field').hide();
                        $('.number').hide();
                        $('.scale').hide();
                        $('.start_rating').hide();
                        $('.ranking').hide();
                        $('.question-input-type').val(9);
                        break;
                    case 'scale':
                        $('.scale').show();
                        $('.button_choice').hide();
                        $('.select_box').hide();
                        $('.single_choice').hide();
                        $('.multiple_choice').hide();
                        $('.single_line').hide();
                        $('.multiple_line').hide();
                        $('.multiple_field').hide();
                        $('.number').hide();
                        $('.percentage').hide();
                        $('.start_rating').hide();
                        $('.ranking').hide();
                        $('.question-input-type').val(11);
                        break;
                    case 'start_rating':
                        $('.start_rating').show();
                        $('.button_choice').hide();
                        $('.select_box').hide();
                        $('.single_choice').hide();
                        $('.multiple_choice').hide();
                        $('.single_line').hide();
                        $('.multiple_line').hide();
                        $('.multiple_field').hide();
                        $('.number').hide();
                        $('.percentage').hide();
                        $('.scale').hide();
                        $('.ranking').hide();
                        $('.question-input-type').val(12);
                        break;
                    case 'ranking':
                        $('.ranking').show();
                        $('.button_choice').hide();
                        $('.select_box').hide();
                        $('.single_choice').hide();
                        $('.multiple_choice').hide();
                        $('.single_line').hide();
                        $('.multiple_line').hide();
                        $('.multiple_field').hide();
                        $('.number').hide();
                        $('.percentage').hide();
                        $('.scale').hide();
                        $('.start_rating').hide();
                        $('.question-input-type').val(13);
                        break;
                }
            });

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
                    },
                    meta_question_field_value: {
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

            $(".add-more-option").click(function(){
                $(".customFields").append(
                    '<tr>' +
                    '<td>' +
                    '<div class="col-md-5">'+
                    '<input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />'+
                    '</div>'+
                    '<div class="col-md-5">'+
                    '<input type="text" class="form-control"  name="question_option_value[]" placeholder="Value" />'+
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

            $('.masking-enable').on('change', function () {
                if ($(this).is(':checked'))
                    $('.show-masking').show();
                else
                    $('.show-masking').hide();
            });
            $('.branching').on('change', function () {
                if ($(this).is(':checked'))
                    $('.enable-branching').show();
                else
                    $('.enable-branching').hide();
            });

        })
    </script>
@endsection