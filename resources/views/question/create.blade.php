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
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
                $totalPage = count($page_numbers);
                if ($totalPage == 0) {
                    $totalPage = '';
                } else {
                    $totalPage = $totalPage+1;
                }
            ?>
            <div class="col-md-2">
                <form id="add-page-form">
                    <div class="form-group ">
                        <input type="text" id="page_number" value="{{ $totalPage }}" class="form-control" name="page_number">
                    </div>
                </form>
            </div>
            <div class="col-md-9">
                <div class="form-group ">
                    <div class="input-group">
                        <button data-campaign-id="{{$campaign_id}}" class="btn btn-primary btn-squared add-page">
                            <i class="fa fa-plus"></i> Add Page
                        </button>
                        {{--<button data-campaign-id="{{$campaign_id}}" class="btn btn-info btn-squared add-branch-question">
                            <i class="fa fa-plus"></i> Add Branch
                        </button>--}}
                        <a href="{{ url('admin/survey/campaign') }}" class=" btn btn-dark-grey btn-squared ">
                            <i class="fa fa-bar-chart-o"></i> All campaign
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if(!empty($page_numbers) && count($page_numbers)>0)
                @foreach($page_numbers as $page)
                    <div class="panel panel-default btn-squared">
                        <div class="panel-heading">
                            <i class="fa fa-external-link-square"></i>
                            Question(s)
                            <div class="panel-tools">
                                <strong>Page : {{ $page->page_number }}</strong>
                                <a class="btn btn-xs btn-link delete_page" data-page-number="{{$page->page_number}}"
                                   data-campaign-id="{{ $page->campaign_id }}"
                                   data-page-id="{{$page->id}}" href="javascript:void(0)">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    $questions = \App\Question::getAllSurveyQuestionByPageNumber($page->page_number,$campaign_id)
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table" style="margin-bottom: 1px;">
                                            @if(!empty($questions) && count($questions)>0)
                                                @foreach($questions as $key => $question)
                                                    <tr>
                                                        <td width="90%">
                                                            <strong>
                                                                <a href="javascript:void(0)" data-campaign-id="{{$question->campaign_id}}" data-question-id="{{$question->id}}"
                                                                   class="edit_question" title="Edit question">Q - {{ $key+1 }} : </a>
                                                                {{ isset($question->question_title) ? $question->question_title: '' }} &nbsp;&nbsp;
                                                                @if ($question->masking_enable == 1)
                                                                    <span class="label label-warning"> masking question</span>
                                                                @endif
                                                                @if ($question->remasking_enable == 1)
                                                                    <span class="label label-inverse"> reverse masking question</span>
                                                                @endif
                                                                @if ($question->branching_enable == 1)
                                                                    <a href="javascript:void(0)" class="label label-warning edit-branching"
                                                                       data-page-number="{{$page->page_number}}" data-campaign-id="{{$campaign_id}}" data-question-id = "{{$question->id}}">
                                                                        branching question
                                                                    </a>
                                                                @endif
                                                            </strong>
                                                            <span class="help-block">{{ isset($question->question_help_text) ? 'Help text: '.$question->question_help_text:'' }}</span>
                                                            @if($question->question_input_type_id == 1)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>
                                                                <table>
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <tr>
                                                                                <td style="width:93%">
                                                                                    <div class="radio">
                                                                                        <label>
                                                                                            <input type="radio" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                            {{$group->question_option_name}}
                                                                                            @if(count($masked_meta) > 0)
                                                                                                @if($group->option_question_mask_ref_id ==$masked_meta->meta_question_field_value)
                                                                                                    <?php
                                                                                                        $maskQTitle = \DB::table('survey_question')
                                                                                                            ->where('id',$masked_meta->meta_question_field_value)
                                                                                                            ->first()
                                                                                                    ?>
                                                                                                    @if(count($maskQTitle)> 0)
                                                                                                        - M.QT : {{ $maskQTitle->question_title }}
                                                                                                    @else
                                                                                                    @endif
                                                                                                @else

                                                                                                @endif
                                                                                            @else

                                                                                            @endif
                                                                                            @if(count($re_masked_meta) > 0)
                                                                                                @if($group->option_question_mask_ref_id ==$re_masked_meta->meta_question_field_value)
                                                                                                    <?php
                                                                                                    $rmaskQTitle = \DB::table('survey_question')
                                                                                                        ->where('id',$re_masked_meta->meta_question_field_value)
                                                                                                        ->first()
                                                                                                    ?>
                                                                                                    @if(count($rmaskQTitle)> 0)
                                                                                                        - RM.QT : {{ $rmaskQTitle->question_title }}
                                                                                                    @else
                                                                                                    @endif
                                                                                                @else

                                                                                                @endif
                                                                                            @else

                                                                                            @endif
                                                                                            <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                        </label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <a class="btn btn-primary btn-xs btn-squared edit_question_option_group"
                                                                                       href="javascript:void(0)" title="edit option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a>
                                                                                    <a class="btn btn-danger btn-xs btn-squared delete_question_option_group" href="javascript:void(0)"
                                                                                       title="delete option" data-question-option-group-id="{{$group->id}}" data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td width="100%">Option not found</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                    @endif
                                                                </table>
                                                            @elseif($question->question_input_type_id == 2)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>
                                                                <table>
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <tr>
                                                                                <td style="width: 93%">
                                                                                    <div class="checkbox">
                                                                                        <label>
                                                                                            <input type="hidden" value="{{$group->option_question_id}}" name="answer_question_id[]">
                                                                                            <input type="checkbox" value="{{$group->id}}" name="answer_option_group_value[]_{{$group->option_question_id}}" class="grey">
                                                                                            {{$group->question_option_name}}
                                                                                            @if(count($masked_meta) > 0)
                                                                                                @if($group->option_question_mask_ref_id ==$masked_meta->meta_question_field_value)
                                                                                                    <?php
                                                                                                    $maskQTitle = \DB::table('survey_question')
                                                                                                        ->where('id',$masked_meta->meta_question_field_value)
                                                                                                        ->first()
                                                                                                    ?>
                                                                                                    @if(count($maskQTitle)> 0)
                                                                                                        - M.QT : {{ $maskQTitle->question_title }}
                                                                                                    @else
                                                                                                    @endif
                                                                                                @else

                                                                                                @endif
                                                                                            @else

                                                                                            @endif
                                                                                            @if(count($re_masked_meta) > 0)
                                                                                                @if($group->option_question_mask_ref_id ==$re_masked_meta->meta_question_field_value)
                                                                                                    <?php
                                                                                                    $rmaskQTitle = \DB::table('survey_question')
                                                                                                        ->where('id',$re_masked_meta->meta_question_field_value)
                                                                                                        ->first()
                                                                                                    ?>
                                                                                                    @if(count($rmaskQTitle)> 0)
                                                                                                        - RM.QT : {{ $rmaskQTitle->question_title }}
                                                                                                    @else
                                                                                                    @endif
                                                                                                @else

                                                                                                @endif
                                                                                            @else

                                                                                            @endif
                                                                                        </label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <a class="btn btn-primary btn-xs btn-squared edit_question_option_group"
                                                                                       href="javascript:void(0)" title="edit option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a>
                                                                                    <a class="btn btn-danger btn-xs btn-squared delete_question_option_group" href="javascript:void(0)"
                                                                                       title="delete option" data-question-option-group-id="{{$group->id}}" data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td width="93%">Option not found</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                    @endif
                                                                </table>
                                                            @elseif($question->question_input_type_id == 3)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>
                                                                <select  class="form-control" name="">
                                                                    @foreach($option_groups as $key=>$group)
                                                                        <option value="{{ $group->id }}">
                                                                            {{$group->question_option_name}}
                                                                            @if(count($masked_meta) > 0)
                                                                                @if($group->option_question_mask_ref_id ==$masked_meta->meta_question_field_value)
                                                                                    <?php
                                                                                    $maskQTitle = \DB::table('survey_question')
                                                                                        ->where('id',$masked_meta->meta_question_field_value)
                                                                                        ->first()
                                                                                    ?>
                                                                                    @if(count($maskQTitle)> 0)
                                                                                        - M.QT : {{ $maskQTitle->question_title }}
                                                                                    @else
                                                                                    @endif
                                                                                @else

                                                                                @endif
                                                                            @else

                                                                            @endif
                                                                            @if(count($re_masked_meta) > 0)
                                                                                @if($group->option_question_mask_ref_id ==$re_masked_meta->meta_question_field_value)
                                                                                    <?php
                                                                                    $rmaskQTitle = \DB::table('survey_question')
                                                                                        ->where('id',$re_masked_meta->meta_question_field_value)
                                                                                        ->first()
                                                                                    ?>
                                                                                    @if(count($rmaskQTitle)> 0)
                                                                                        - RM.QT : {{ $rmaskQTitle->question_title }}
                                                                                    @else
                                                                                    @endif
                                                                                @else

                                                                                @endif
                                                                            @else

                                                                            @endif
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            @elseif($question->question_input_type_id == 4)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>
                                                                <table>
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <tr>
                                                                                <td style="width: 93%">
                                                                                    <strong>Option - {{ $key+1 }}:</strong>  {{$group->question_option_name}}
                                                                                    @if(count($masked_meta) > 0)
                                                                                        @if($group->option_question_mask_ref_id ==$masked_meta->meta_question_field_value)
                                                                                            <?php
                                                                                            $maskQTitle = \DB::table('survey_question')
                                                                                                ->where('id',$masked_meta->meta_question_field_value)
                                                                                                ->first()
                                                                                            ?>
                                                                                            @if(count($maskQTitle)> 0)
                                                                                                - M.QT : {{ $maskQTitle->question_title }}
                                                                                            @else
                                                                                            @endif
                                                                                        @else

                                                                                        @endif
                                                                                    @else

                                                                                    @endif
                                                                                    @if(count($re_masked_meta) > 0)
                                                                                        @if($group->option_question_mask_ref_id ==$re_masked_meta->meta_question_field_value)
                                                                                            <?php
                                                                                            $rmaskQTitle = \DB::table('survey_question')
                                                                                                ->where('id',$re_masked_meta->meta_question_field_value)
                                                                                                ->first()
                                                                                            ?>
                                                                                            @if(count($rmaskQTitle)> 0)
                                                                                                - RM.QT : {{ $rmaskQTitle->question_title }}
                                                                                            @else
                                                                                            @endif
                                                                                        @else

                                                                                        @endif
                                                                                    @else

                                                                                    @endif
                                                                                    <?php
                                                                                        $question_option_group_details = \DB::table('question_option_group_details')
                                                                                            ->where('question_option_group_id',$group->id)
                                                                                            ->get();
                                                                                    ?>
                                                                                    @if(!empty($question_option_group_details) && count($question_option_group_details) > 0)
                                                                                        <table>
                                                                                            @foreach($question_option_group_details as $key => $question_option_group_detail)
                                                                                            <tr>
                                                                                                <td>
                                                                                                    L-{{$key+1}}:
                                                                                                    <label class="btn btn-xs btn-default btn-squared">
                                                                                                        <input type="radio" name="o_name_{{$question_option_group_detail->question_option_group_id}}" id="option{{$question_option_group_detail->o_value}}">
                                                                                                        {{$question_option_group_detail->o_name}}
                                                                                                    </label>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <a href="{{ url('admin/question/option/group/label/delete/'.$question_option_group_detail->id) }}"
                                                                                                       onclick="if (confirm('Delete selected label name?')){return true;}else{event.stopPropagation(); event.preventDefault();};">delete</a>
                                                                                                </td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                        </table>
                                                                                    @else
                                                                                        <a href="javascript:void(0);" data-question-option-group-id = "{{$group->id}}" class="add-label-name">please enter label name</a>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    &nbsp;&nbsp;
                                                                                    <a class="btn btn-primary btn-xs btn-squared edit-option-label-name"
                                                                                       href="javascript:void(0)" title="edit option" data-question-option-group-id="{{$group->id}}">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a>
                                                                                    <a class="btn btn-danger btn-xs btn-squared delete_question_option_group" href="javascript:void(0)"
                                                                                       title="delete option" data-question-option-group-id="{{$group->id}}" data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td width="93%">Option not found</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                    @endif
                                                                </table>
                                                            @elseif($question->question_input_type_id == 5)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>

                                                                <table>
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <tr>
                                                                                <td style="width: 93%">
                                                                                    <input type="text" class="form-control" name="">
                                                                                </td>
                                                                                <td>
                                                                                    &nbsp;&nbsp;
                                                                                    <a class="btn btn-primary btn-xs btn-squared edit_question_option_group"
                                                                                       href="javascript:void(0)" title="edit option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a>
                                                                                    <a class="btn btn-danger btn-xs btn-squared delete_question_option_group" href="javascript:void(0)"
                                                                                       title="delete option" data-question-option-group-id="{{$group->id}}" data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td width="93%">Option not found</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                    @endif
                                                                </table>
                                                            @elseif($question->question_input_type_id == 6)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>
                                                                <table>
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <tr>
                                                                                <td width="93%"><textarea class="form-control"></textarea></td>
                                                                                <td>
                                                                                    &nbsp;&nbsp;
                                                                                    <a class="btn btn-primary btn-xs btn-squared edit_question_option_group"
                                                                                       href="javascript:void(0)" title="edit option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a>
                                                                                    <a class="btn btn-danger btn-xs btn-squared delete_question_option_group" href="javascript:void(0)"
                                                                                       title="delete option" data-question-option-group-id="{{$group->id}}" data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td width="85%">Option not found</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                    @endif
                                                                </table>
                                                            @elseif($question->question_input_type_id == 7)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>
                                                                <table>
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <tr>
                                                                                <td width="93%">
                                                                                    <label><strong>
                                                                                            {{$key+1}} : {{$group->question_option_name}}
                                                                                            @if(count($masked_meta) > 0)
                                                                                                @if($group->option_question_mask_ref_id ==$masked_meta->meta_question_field_value)
                                                                                                    <?php
                                                                                                    $maskQTitle = \DB::table('survey_question')
                                                                                                        ->where('id',$masked_meta->meta_question_field_value)
                                                                                                        ->first()
                                                                                                    ?>
                                                                                                    @if(count($maskQTitle)> 0)
                                                                                                        - M.QT : {{ $maskQTitle->question_title }}
                                                                                                    @else
                                                                                                    @endif
                                                                                                @else

                                                                                                @endif
                                                                                            @else

                                                                                            @endif
                                                                                            @if(count($re_masked_meta) > 0)
                                                                                                @if($group->option_question_mask_ref_id ==$re_masked_meta->meta_question_field_value)
                                                                                                    <?php
                                                                                                    $rmaskQTitle = \DB::table('survey_question')
                                                                                                        ->where('id',$re_masked_meta->meta_question_field_value)
                                                                                                        ->first()
                                                                                                    ?>
                                                                                                    @if(count($rmaskQTitle)> 0)
                                                                                                        - RM.QT : {{ $rmaskQTitle->question_title }}
                                                                                                    @else
                                                                                                    @endif
                                                                                                @else

                                                                                                @endif
                                                                                            @else

                                                                                            @endif
                                                                                        </strong>
                                                                                    </label>
                                                                                    <input type="text" class="form-control" name="">
                                                                                </td>
                                                                                <td>
                                                                                    &nbsp;&nbsp;
                                                                                    <a class="btn btn-primary btn-xs btn-squared edit_question_option_group" style="margin-top: 20px;"
                                                                                       href="javascript:void(0)" title="edit option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a>
                                                                                    <a class="btn btn-danger btn-xs btn-squared delete_question_option_group" style="margin-top: 20px;"
                                                                                       href="javascript:void(0)" title="delete option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td width="85%">Option not found</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                    @endif
                                                                </table>
                                                            @elseif($question->question_input_type_id == 8)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>
                                                                <table>
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <tr>
                                                                                <td width="93%">
                                                                                    {{--<label><strong>{{$key+1}} : {{$group->question_option_name}}</strong></label>--}}
                                                                                    <input type="text" class="form-control number_check" name=""
                                                                                           placeholder="{{ isset($group->min) ? 'Min Range:'.$group->min : '' }}  {{ isset($group->max) ? 'Max Range:'.$group->max : '' }}">
                                                                                </td>
                                                                                <td>
                                                                                    &nbsp;&nbsp;
                                                                                    <a class="btn btn-primary btn-xs btn-squared edit_question_option_group" style="margin-top: 20px;"
                                                                                       href="javascript:void(0)" title="edit option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a>
                                                                                    <a class="btn btn-danger btn-xs btn-squared delete_question_option_group" style="margin-top: 20px;"
                                                                                       href="javascript:void(0)" title="delete option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td width="85%">Option not found</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                    @endif
                                                                </table>
                                                            @elseif($question->question_input_type_id == 9)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>
                                                                <table>
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <tr>
                                                                                <td width="93%">
                                                                                    {{--<label><strong>{{$key+1}} : {{$group->question_option_name}}</strong></label>--}}
                                                                                    <input type="text" class="form-control percentage_check" name=""
                                                                                            placeholder="{{ isset($group->min) ? 'Min Range:'.$group->min : '' }}  {{ isset($group->max) ? 'Max Range:'.$group->max : '' }}" >
                                                                                </td>
                                                                                <td>
                                                                                    &nbsp;&nbsp;
                                                                                    <a class="btn btn-primary btn-xs btn-squared edit_question_option_group" style="margin-top: 20px;"
                                                                                       href="javascript:void(0)" title="edit option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a>
                                                                                    <a class="btn btn-danger btn-xs btn-squared delete_question_option_group" style="margin-top: 20px;"
                                                                                       href="javascript:void(0)" title="delete option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td width="85%">Option not found</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                    @endif
                                                                </table>
                                                            @elseif($question->question_input_type_id == 10)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>
                                                                <table>
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <tr>
                                                                                <td style="width: 93%">
                                                                                    <strong>Option - {{ $key+1 }}:</strong>
                                                                                    {{$group->question_option_name}}
                                                                                    @if(count($masked_meta) > 0)
                                                                                        @if($group->option_question_mask_ref_id ==$masked_meta->meta_question_field_value)
                                                                                            <?php
                                                                                            $maskQTitle = \DB::table('survey_question')
                                                                                                ->where('id',$masked_meta->meta_question_field_value)
                                                                                                ->first()
                                                                                            ?>
                                                                                            @if(count($maskQTitle)> 0)
                                                                                                - M.QT : {{ $maskQTitle->question_title }}
                                                                                            @else
                                                                                            @endif
                                                                                        @else

                                                                                        @endif
                                                                                    @else

                                                                                    @endif
                                                                                    @if(count($re_masked_meta) > 0)
                                                                                        @if($group->option_question_mask_ref_id ==$re_masked_meta->meta_question_field_value)
                                                                                            <?php
                                                                                            $rmaskQTitle = \DB::table('survey_question')
                                                                                                ->where('id',$re_masked_meta->meta_question_field_value)
                                                                                                ->first()
                                                                                            ?>
                                                                                            @if(count($rmaskQTitle)> 0)
                                                                                                - RM.QT : {{ $rmaskQTitle->question_title }}
                                                                                            @else
                                                                                            @endif
                                                                                        @else

                                                                                        @endif
                                                                                    @else

                                                                                    @endif
                                                                                    <?php
                                                                                    $question_option_group_details = \DB::table('question_option_group_details')
                                                                                        ->where('question_option_group_id',$group->id)
                                                                                        ->get();
                                                                                    ?>
                                                                                    @if(!empty($question_option_group_details) && count($question_option_group_details) > 0)
                                                                                        <select class="form-control pull-left" name="">
                                                                                            <option value="">Select scale</option>
                                                                                            @foreach($question_option_group_details as $key => $question_option_group_detail)
                                                                                                <option value="{{$question_option_group_detail->o_value}}">{{$question_option_group_detail->o_name}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    @else
                                                                                        <a href="javascript:void(0);" data-question-option-group-id = "{{$group->id}}" class="add-label-name">please enter label name</a>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    &nbsp;&nbsp;
                                                                                    <a class="btn btn-primary btn-xs btn-squared edit-option-label-name"
                                                                                       href="javascript:void(0)" title="edit option" data-question-option-group-id="{{$group->id}}">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a>
                                                                                    <a class="btn btn-danger btn-xs btn-squared delete_question_option_group" href="javascript:void(0)"
                                                                                       title="delete option" data-question-option-group-id="{{$group->id}}" data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td width="93%">Option not found</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                    @endif
                                                                </table>
                                                            @elseif($question->question_input_type_id == 11)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>
                                                                <table>
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <tr>
                                                                                <td width="93%">
                                                                                    <label><strong>{{$key+1}} :
                                                                                            {{$group->question_option_name}}
                                                                                            @if(count($masked_meta) > 0)
                                                                                                @if($group->option_question_mask_ref_id ==$masked_meta->meta_question_field_value)
                                                                                                    <?php
                                                                                                    $maskQTitle = \DB::table('survey_question')
                                                                                                        ->where('id',$masked_meta->meta_question_field_value)
                                                                                                        ->first()
                                                                                                    ?>
                                                                                                    @if(count($maskQTitle)> 0)
                                                                                                        - M.QT : {{ $maskQTitle->question_title }}
                                                                                                    @else
                                                                                                    @endif
                                                                                                @else

                                                                                                @endif
                                                                                            @else

                                                                                            @endif
                                                                                            @if(count($re_masked_meta) > 0)
                                                                                                @if($group->option_question_mask_ref_id ==$re_masked_meta->meta_question_field_value)
                                                                                                    <?php
                                                                                                    $rmaskQTitle = \DB::table('survey_question')
                                                                                                        ->where('id',$re_masked_meta->meta_question_field_value)
                                                                                                        ->first()
                                                                                                    ?>
                                                                                                    @if(count($rmaskQTitle)> 0)
                                                                                                        - RM.QT : {{ $rmaskQTitle->question_title }}
                                                                                                    @else
                                                                                                    @endif
                                                                                                @else

                                                                                                @endif
                                                                                            @else

                                                                                            @endif
                                                                                        </strong>
                                                                                    </label><br>

                                                                                    <fieldset class="rating">
                                                                                        @for ($i = $group->question_option_value;$i >=1; $i--)
                                                                                            <span class="star"></span>
                                                                                            <input type="radio" id="star{{$i}}{{$group->id}}" class="myClassName" name="user_answer[]" value="{{ $i }}" />
                                                                                            <label for="star{{$i}}{{$group->id}}" >{{$i}} stars</label>
                                                                                        @endfor
                                                                                    </fieldset>
                                                                                    {{--@if(is_numeric($group->question_option_value))
                                                                                        <span class="rating">
                                                                                            @for ($i = 1; $i <=$group->question_option_value; $i++)
                                                                                                <span class="star"></span>
                                                                                            @endfor
                                                                                        </span>
                                                                                    @else
                                                                                        <strong>Option value must be a number</strong>
                                                                                    @endif
                                                                                    <span class="rating">
                                                                                        <span class="star">ddd</span>
                                                                                    </span>--}}

                                                                                </td>
                                                                                <td>
                                                                                    &nbsp;&nbsp;
                                                                                    <a class="btn btn-primary btn-xs btn-squared edit_question_option_group" style="margin-top: 20px;"
                                                                                       href="javascript:void(0)" title="edit option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a>
                                                                                    <a class="btn btn-danger btn-xs btn-squared delete_question_option_group" style="margin-top: 20px;"
                                                                                       href="javascript:void(0)" title="delete option" data-question-option-group-id="{{$group->id}}"
                                                                                       data-question-id="{{$group->option_question_id}}">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td width="85%">Option not found</td>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                    @endif
                                                                </table>
                                                            @elseif($question->question_input_type_id == 12)
                                                                <?php
                                                                $option_groups = \DB::table('question_option_group')
                                                                    ->where('option_question_id',$question->id)
                                                                    ->where('option_input_type_id',$question->question_input_type_id)
                                                                    ->get();
                                                                $masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                $re_masked_meta = \DB::table('question_meta')
                                                                    ->where('question_id',$question->id)
                                                                    ->where('meta_question_field_name','re_masked_question_id')
                                                                    ->where('campaign_id',$campaign_id)
                                                                    ->first();
                                                                ?>
                                                                <ul class="list-group example" style="list-style: none">
                                                                    @if(!empty($option_groups) && count($option_groups) > 0)
                                                                        @foreach($option_groups as $key=>$group)
                                                                            <li class="list-group-item" style="cursor: pointer; border-radius: 0px;">
                                                                                {{$group->question_option_name}}
                                                                                @if(count($masked_meta) > 0)
                                                                                    @if($group->option_question_mask_ref_id ==$masked_meta->meta_question_field_value)
                                                                                        <?php
                                                                                        $maskQTitle = \DB::table('survey_question')
                                                                                            ->where('id',$masked_meta->meta_question_field_value)
                                                                                            ->first()
                                                                                        ?>
                                                                                        @if(count($maskQTitle)> 0)
                                                                                            - M.QT : {{ $maskQTitle->question_title }}
                                                                                        @else
                                                                                        @endif
                                                                                    @else

                                                                                    @endif
                                                                                @else

                                                                                @endif
                                                                                @if(count($re_masked_meta) > 0)
                                                                                    @if($group->option_question_mask_ref_id ==$re_masked_meta->meta_question_field_value)
                                                                                        <?php
                                                                                        $rmaskQTitle = \DB::table('survey_question')
                                                                                            ->where('id',$re_masked_meta->meta_question_field_value)
                                                                                            ->first()
                                                                                        ?>
                                                                                        @if(count($rmaskQTitle)> 0)
                                                                                            - RM.QT : {{ $rmaskQTitle->question_title }}
                                                                                        @else
                                                                                        @endif
                                                                                    @else

                                                                                    @endif
                                                                                @else

                                                                                @endif
                                                                                <a class="btn btn-danger btn-xs btn-squared delete_question_option_group pull-right" href="javascript:void(0)"
                                                                                   title="delete option" data-question-option-group-id="{{$group->id}}" data-question-id="{{$group->option_question_id}}">
                                                                                    <i class="fa fa-trash-o"></i>
                                                                                </a>
                                                                                <a class="btn btn-primary btn-xs btn-squared edit_question_option_group pull-right"
                                                                                   href="javascript:void(0)" title="edit option" data-question-option-group-id="{{$group->id}}"
                                                                                   data-question-id="{{$group->option_question_id}}">
                                                                                    <i class="fa fa-edit"></i>
                                                                                </a>
                                                                            </li>
                                                                        @endforeach
                                                                    @else
                                                                        <li>Option Not found</li>
                                                                    @endif
                                                                </ul>

                                                            @endif
                                                        </td>
                                                        <td style="vertical-align: top;">
                                                            <a href="javascript:void(0)" data-campaign-id="{{$question->campaign_id}}" data-question-id="{{$question->id}}"
                                                               class="btn btn-xs btn-warning btn-squared add-question-option" title="Add option name"><i class="fa fa-plus"></i> </a>
                                                            <a href="javascript:void(0)" data-campaign-id="{{$question->campaign_id}}" data-question-id="{{$question->id}}"
                                                               class="btn btn-xs btn-success btn-squared edit_question" title="Edit question"><i class="fa fa-edit"></i> </a>
                                                            <a href="javascript:void(0)" data-campaign-id="{{$question->campaign_id}}" data-question-id="{{$question->id}}"
                                                               class="btn btn-xs btn-danger btn-squared delete_question" title="Delete question"><i class="fa fa-trash-o"></i></a>
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
                        <div class="panel-footer">
                            <a href="javascript:void(0);" data-page-number="{{$page->page_number}}" data-campaign-id="{{$campaign_id}}"
                               class=" btn btn-success btn-squared add-more-question">
                                <i class="fa fa-plus"></i> Add question
                            </a>
                            <a href="javascript:void(0);" data-page-number="{{$page->page_number +1}}" data-campaign-id="{{$campaign_id}}"
                               class=" btn btn-primary btn-squared add-page-after">
                                <i class="fa fa-plus"></i> Add Page
                            </a>
                            <a href="javascript:void(0);" data-page-number="{{$page->page_number}}" data-campaign-id="{{$campaign_id}}"
                               class=" btn btn-info btn-squared add-branch-question">
                                <i class="fa fa-plus"></i> Add Branch
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="panel panel-default btn-squared">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Page not found</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!--modal for question add -->
    <div id="ajax-more-question" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;" data-width="65%"></div>
    <!--modal for question add -->
    <!--modal for question edit -->
    <div id="ajax-question-edit" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;" data-width="70%"></div>
    <!--modal for question edit -->
    <!--modal for question option add group-->
    <div id="ajax-question-option-group-add" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" style="display: none;" data-width="45%"></div>
    <!--modal for question option add group -->
    <!--modal for question option edit group-->
    <div id="ajax-question-option-group-edit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" style="display: none;" data-width="45%"></div>
    <!--modal for question option edit group -->
    <!--modal for branch-->
    <div id="ajax-branch-question" class="modal fade"  data-backdrop="static" data-keyboard="false" tabindex="-1" style="display: none;" data-width="65%"></div>
    <!--modal for branch -->
    <!--modal for  option  group label-->
    <div id="ajax-label-question" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" style="display: none;" data-width="45%"></div>
    <!--modal for  option  group label -->
    <!--modal for  option  group label edit-->
    <div id="ajax-edit-label-question" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" style="display: none;" data-width="45%"></div>
    <!--modal for  option  group label edit -->

    <!--modal for  edit  branch  -->
    <div id="ajax-edit-branch-question" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" style="display: none;" data-width="65%"></div>
    <!--modal for  branch edit -->




@endsection
@section('JScript')
    <script>
        $(function () {
            var site_url = $('.site_url').val();
            $('#add-page-form').validate({
                rules: {
                    page_number: {
                        required: true,
                        digits: true,
                        min: 1,
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
                },
                submitHandler: function(form) {
                    return false;
                }
            });
            $('.add-page').on('click', function () {
                if($("#add-page-form").valid()){
                    var page_number = $('#page_number').val();
                    var campaign_id = $(this).data('campaign-id');
                    if(page_number != '') {
                        $.ajax({
                            Type: "GET",
                            url: site_url + '/campaign/page/setting/'+campaign_id+'/'+page_number,
                            success: function (data) {
                                if (data.status == 1) {
                                    window.location.reload();
                                } else if (data.status == -1) {
                                    $.jGrowl("Campaign or page number not found.",
                                        {
                                            life: 10000,
                                            position:"bottom-right",
                                            theme: 'custom',
                                        }
                                    );
                                } else if (data.status == 0) {
                                    $.jGrowl("Page number is not added.",
                                        {
                                            life: 10000,
                                            position:"bottom-right",
                                            theme: 'custom',
                                        }
                                    );
                                } else if (data.status == 2){
                                    $.jGrowl("Page number already exist.",
                                        {
                                            life: 10000,
                                            position:"bottom-right",
                                            theme: 'custom',
                                        }
                                    );
                                } else {
                                    $.jGrowl("Something is going wrong",
                                        {
                                            life: 10000,
                                            position:"bottom-right",
                                            theme: 'custom',
                                        }
                                    );
                                }
                            },
                            dataType:"json"
                        });
                    } else {
                        $.jGrowl("Page number not found.",
                            {
                                life: 10000,
                                position:"bottom-right",
                                theme: 'custom',
                            }
                        );
                    }
                } else {
                    $.jGrowl("Please enter page number",
                        {
                            life: 10000,
                            position:"bottom-right",
                            theme: 'custom',
                        }
                    );
                }
            });


            $('.add-page-after').on('click', function () {
                var page_number = $(this).data('page-number');
                var campaign_id = $(this).data('campaign-id');
                if(page_number != '' && campaign_id != '') {
                    $.ajax({
                        Type: "GET",
                        url: site_url + '/campaign/page/setting/after/'+campaign_id+'/'+page_number,
                        success: function (data) {
                            if (data.status == 1) {
                                window.location.reload();
                            } else {
                                $.jGrowl("Something is going wrong",
                                    {
                                        life: 10000,
                                        position:"bottom-right",
                                        theme: 'custom',
                                    }
                                );
                            }
                        },
                        dataType:"json"
                    });
                } else {
                    $.jGrowl("Something is wrong",
                        {
                            life: 10000,
                            position:"bottom-right",
                            theme: 'custom',
                        }
                    );
                }

            });

            $('.delete_page').on('click', function (e) {
                e.preventDefault();

                var id = $(this).data('page-id');
                var page_number = $(this).data('page-number');
                var campaign_id = $(this).data('campaign-id');

                bootbox.dialog({
                    message: "Are you sure you want to delete ? if confirm, you may be lost all question(s) of this page.",
                    title: "<i class='glyphicon glyphicon-trash'></i> Delete !",
                    buttons: {
                        success: {
                            label: "No",
                            className: "btn-success btn-squared",
                            callback: function() {
                                $('.bootbox').modal('hide');
                            }
                        },
                        danger: {
                            label: "Delete!",
                            className: "btn-danger btn-squared",
                            callback: function() {
                                $.ajax({
                                    type: 'GET',
                                    url: site_url+'/campaign/page/delete/'+id+'/'+campaign_id+'/'+page_number,
                                }).done(function(response){
                                    bootbox.alert(response);
                                    /*parent.fadeOut(10,function () {
                                        location.reload(true);
                                    });*/
                                    location.reload(true);
                                }).fail(function(response){
                                    bootbox.alert(response);
                                })
                            }
                        }
                    }
                });
            });

            $('.delete_question').on('click', function (e) {
                e.preventDefault();
                var campaign_id = $(this).data('campaign-id');
                var question_id = $(this).data('question-id');
                bootbox.dialog({
                    message: "Are you sure you want to delete this question ? if confirm, you may be lost all option(s) of this question.",
                    title: "<i class='glyphicon glyphicon-trash'></i> Delete !",
                    buttons: {
                        success: {
                            label: "No",
                            className: "btn-success btn-squared",
                            callback: function() {
                                $('.bootbox').modal('hide');
                            }
                        },
                        danger: {
                            label: "Delete!",
                            className: "btn-danger btn-squared",
                            callback: function() {
                                $.ajax({
                                    type: 'GET',
                                    url: site_url+'/admin/question/delete/'+campaign_id+'/'+question_id,
                                }).done(function(response){
                                    bootbox.alert(response);
                                    location.reload(true);
                                }).fail(function(response){
                                    bootbox.alert(response);
                                })
                            }
                        }
                    }
                });
            })
            $('.delete_question_option_group').on('click', function (e) {
                e.preventDefault();
                var id = $(this).data('question-option-group-id');
                var question_id = $(this).data('question-id');
                bootbox.dialog({
                    message: "Are you sure you want to delete this option name ?",
                    title: "<i class='glyphicon glyphicon-trash'></i> Delete !",
                    buttons: {
                        success: {
                            label: "No",
                            className: "btn-success btn-squared",
                            callback: function() {
                                $('.bootbox').modal('hide');
                            }
                        },
                        danger: {
                            label: "Delete!",
                            className: "btn-danger btn-squared",
                            callback: function() {
                                $.ajax({
                                    type: 'GET',
                                    url: site_url+'/admin/question/option/group/delete/'+id+'/'+question_id,
                                }).done(function(response){
                                    bootbox.alert(response);
                                    location.reload(true);
                                }).fail(function(response){
                                    bootbox.alert(response);
                                })
                            }
                        }
                    }
                });
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
        });
    </script>
@endsection
