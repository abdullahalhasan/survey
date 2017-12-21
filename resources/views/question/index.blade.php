@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="panel panel-default btn-squared">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i>
                    All Question
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
                    {{--<div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <a href="{{ url('admin/question/create') }}" class=" btn btn-success btn-squared ">
                                    <i class="fa fa-plus"></i> Create
                                </a>
                            </div>
                        </div>
                    </div>--}}
                    <div class="table-responsive table_date">
                        <table class="table table-hover table-bordered table-striped nopadding">
                            <thead>
                            <tr>
                                <th>Campaign Category</th>
                                <th>Campaign Name</th>
                                <th>Question Name</th>
                                <th>Question Help Text</th>
                                <th>Question Input Type</th>
                                <th>Question Page No</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($questions) && count($questions) > 0)
                                @foreach($questions as $question)
                                    <?php
                                      $categoryName = \App\SurveyCampaign::getCampaignTitleById($question->campaign_id)
                                    ?>
                                    <tr>
                                        <td>{{$categoryName->name}}</td>
                                        <td>{{ $question->campaign_title }}</td>
                                        <td>{{ $question->question_title }}</td>
                                        <td>{{ $question->question_help_text }}</td>
                                        <td>{{ $question->input_type_name}}</td>
                                        <td>{{ $question->question_page_no}}</td>
                                        <td>
                                            <a class="btn btn-info btn-xs btn-squared survey-question-show" href="javascript:void(0)"
                                               data-survey-question-id="{{$question->id}}">
                                                <i class="fa fa-search"></i> Show
                                            </a>
                                            <a class="btn btn-primary btn-xs btn-squared" href="{{ url('admin/question/edit/'.$question->campaign_id.'/'.$question->id) }}">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a class="btn btn-danger btn-xs btn-squared delete"  href="javascript:void(0)"
                                               data-company-id="{{$question->id}}">
                                                <i class="fa fa-trash-o"></i> Delete
                                            </a>
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
                        {{isset($question_pagination) ? $question_pagination:""}}
                    </div>
                </div>
            </div>
            <!-- end: FORM VALIDATION 2 PANEL -->
        </div>
    </div>
    <!--modal for company input type-->
    <div id="ajax-survey-question-show" class="modal fade" tabindex="-1" style="display: none;" data-width="50%"></div>
    <!--modal for company input type-->
@endsection

@section('JScript')
    <script>
    </script>
@endsection