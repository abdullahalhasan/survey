<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><br>
    <h4 class="modal-title">Edit Question</h4>
</div>
<form id="edit-question-form" action="{{ url('admin/question/update/'.$question->campaign_id.'/'.$question->id) }}" role="form" class="form-horizontal" method="post">
    <div class="modal-body">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Question Title
                    <span class="symbol required" aria-required="true"></span>
                </strong>
            </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="question_title" value="{{ $question->question_title }}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>Help Text</strong>
            </label>
            <div class="col-sm-8">
                <input class="form-control" name="question_help_text"  type="text" value="{{ $question->question_help_text }}" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Input type
                    <span class="symbol required" aria-required="true"></span>
                </strong>
            </label>
            <div class="col-sm-8">
                <select  class="form-control search-select question-type" name="question_input_type_id">
                    <option value="">&nbsp;Choice Question Input Type </option>
                    @if(!empty($input_types) && count($input_types) > 0)
                        @foreach($input_types as $input_type)
                            <option value="{{ $input_type->id }}"
                                    {{$input_type->id == $question->question_input_type_id ? "selected" : "" }}>
                                {{$input_type->input_type_name}}
                            </option>
                        @endforeach
                    @else
                        <option value="">Data not found.</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Enable Masking ?
                </strong>
            </label>
            <div class="col-sm-1">
                @if($question->masking_enable == 1)
                    <label class="checkbox-inline">
                        <input type="checkbox" value="1" checked="checked" class="masking-enable" name="masking_enable">
                        Yes
                    </label>
                @else
                    <label class="checkbox-inline">
                        <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                        Yes
                    </label>
                @endif
            </div>
            <div class="col-sm-7">
                <div class="show-masking">
                    <select  class="form-control search-select masking-question" name="mask_question_id">
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
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Enable Reverse Masking ?
                </strong>
            </label>
            <div class="col-sm-1">
                @if($question->remasking_enable == 1)
                    <label class="checkbox-inline">
                        <input type="checkbox" value="1" class="re-masking-enable" checked="checked" name="remasking_enable">
                        Yes
                    </label>
                @else
                    <label class="checkbox-inline">
                        <input type="checkbox" value="1" class="re-masking-enable" name="remasking_enable">
                        Yes
                    </label>
                @endif
            </div>
            <div class="col-sm-7">
                <div class="show-re-masking">
                    <select  class="form-control search-select re-masking-question" name="re_mask_question_id">
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
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Is answer require ?
                </strong>
            </label>
            <div class="col-sm-2 control-label">
                @if($question->question_answer_require == 1)
                    <input type="checkbox" value="1" name="question_answer_require" checked="checked"> Yes
                @else
                    <input type="checkbox" value="1" name="question_answer_require"> Yes
                @endif
            </div>
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Is option random ?
                </strong>
            </label>
            <div class="col-sm-2 control-label">
                @if($question->option_random == 1)
                    <input type="checkbox" value="1" name="option_random" checked="checked"> Yes
                @else
                    <input type="checkbox" value="1" name="option_random"> Yes
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default btn-squared">Close</button>
        <button type="submit" class="btn btn-success btn-squared">Update</button>
    </div>
</form>

<script type="text/javascript">
    jQuery(document).ready(function() {
        var site_url = $('.site_url').val();
        FormElements.init();
        $('.show-masking').hide();
        $('.show-re-masking').hide();
        $('#edit-question-form').validate({
            rules: {
                question_input_type_id: {
                    required: true
                },
                question_title: {
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
        $('.masking-enable').on('change', function () {

            if ($(this).is(':checked')) {
                var page_number = $('.page-number').val();
                var campaign_id = $('.campaign-id').val();
                $('.re-masking-enable').attr("disabled", true);
                if (page_number == '' || campaign_id == '') {
                    alert('Something is wrong');
                    $('.masking-enable').prop('checked', false);
                } else {
                    $.ajax({
                        type:"GET",
                        url:site_url + '/admin/question/ajax/check/masking/remasking/'+campaign_id+'/'+page_number,
                        success: function (data) {
                            if(data.status == 1) {
                                $('.show-masking').show();
                                $('.masking-question').on('change',function () {
                                    var question_id = $(this).val();
                                    console.log(question_id);
                                    if(question_id != '') {
                                        $.ajax({
                                            type:"GET",
                                            url:site_url + '/question/page/number/check/'+campaign_id+'/'+page_number+'/'+question_id,
                                            success: function (data) {
                                                if(data.status == 0) {
                                                    //$(".masking-question option[value='']").prop('selected', true);
                                                    $('.masking-question').val('').trigger('change.select2');
                                                    alert('Please select different question');
                                                } else if (data.status == 1) {
                                                    $.ajax({
                                                        type:"GET",
                                                        url:site_url + '/mask/question/input/option/value/'+question_id,
                                                        success: function (data) {
                                                            if(data.status == 0) {
                                                                //$(".masking-question option[value='']").prop('selected', true);
                                                                $('.masking-question').val('').trigger('change.select2');
                                                                alert('Please select different question');
                                                            } else {
                                                                /*var htmlText = '';
                                                                 for (var i in data) {
                                                                 htmlText += '<tr>';
                                                                 htmlText += '<td>';
                                                                 htmlText += '<input type="text" class="form-control" value="'+data[i].question_option_value +'"   name="question_option_value[]"/>';
                                                                 htmlText += '</td>';
                                                                 htmlText += '<td>';
                                                                 htmlText += '<input type="text" class="form-control" value="'+data[i].question_option_name+'"   name="question_option_value[]"/>';
                                                                 htmlText += '</td>';
                                                                 htmlText += '</tr>';
                                                                 }
                                                                 $('.masked_option').append(htmlText);
                                                                 $('.masked_option').show();*/
                                                            }

                                                        },
                                                        dataType: 'json'
                                                    });
                                                }
                                            },
                                            dataType: "json"
                                        });
                                    } else {
                                        alert('Please choice right question')
                                    }
                                })
                            } else {
                                alert('This page already have masked or reverse masked question.')
                                $('.masking-enable').prop('checked', false);
                            }
                        },
                        dataType: "json"
                    })
                }
            } else {
                $('.re-masking-enable').removeAttr("disabled");
                $('.show-masking').hide();
                //$('.masked_option').hide();
            }
        });
        $('.re-masking-enable').on('change', function () {
            if ($(this).is(':checked')) {
                var page_number = $('.page-number').val();
                var campaign_id = $('.campaign-id').val();
                $('.masking-enable').attr("disabled", true);
                if (page_number == '' || campaign_id == '') {
                    alert('Something is wrong');
                    $('.re-masking-enable').prop('checked', false);
                } else {
                    $.ajax({
                        type:"GET",
                        url:site_url + '/admin/question/ajax/check/masking/remasking/'+campaign_id+'/'+page_number,
                        success: function (data) {
                            if(data.status == 1) {
                                $('.show-re-masking').show();
                                $('.re-masking-question').on('change',function () {
                                    var question_id = $(this).val();
                                    console.log(question_id);
                                    if(question_id != '') {
                                        $.ajax({
                                            type:"GET",
                                            url:site_url + '/question/page/number/check/'+campaign_id+'/'+page_number+'/'+question_id,
                                            success: function (data) {
                                                if(data.status == 0) {
                                                    //$(".masking-question option[value='']").prop('selected', true);
                                                    $('.re-masking-question').val('').trigger('change.select2');
                                                    alert('Please select different question');
                                                } else if (data.status == 1) {
                                                    $.ajax({
                                                        type:"GET",
                                                        url:site_url + '/remask/question/input/option/value/'+question_id,
                                                        success: function (data) {
                                                            if(data.status == 0) {
                                                                //$(".masking-question option[value='']").prop('selected', true);
                                                                $('.re-masking-question').val('').trigger('change.select2');
                                                                alert('Please select different question');
                                                            } else {
                                                                /*var htmlText = '';
                                                                 for (var i in data) {
                                                                 htmlText += '<tr>';
                                                                 htmlText += '<td>';
                                                                 htmlText += '<input type="text" class="form-control" value="'+data[i].question_option_value +'"   name="question_option_value[]"/>';
                                                                 htmlText += '</td>';
                                                                 htmlText += '<td>';
                                                                 htmlText += '<input type="text" class="form-control" value="'+data[i].question_option_name+'"   name="question_option_value[]"/>';
                                                                 htmlText += '</td>';
                                                                 htmlText += '</tr>';
                                                                 }
                                                                 $('.masked_option').append(htmlText);
                                                                 $('.masked_option').show();*/
                                                            }

                                                        },
                                                        dataType: 'json'
                                                    });
                                                }
                                            },
                                            dataType: "json"
                                        });
                                    } else {
                                        alert('Please choice right question')
                                    }
                                })
                            } else {
                                alert('This page already have masked or reverse masked question.')
                                $('.masking-enable').prop('checked', false);
                            }
                        },
                        dataType: "json"
                    })
                }
            } else {
                $('.masking-enable').removeAttr("disabled");
                $('.show-re-masking').hide();
            }
        });
    });
</script>
